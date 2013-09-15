#!/usr/bin/perl 
#
# Clean out redundant data from pre-1.0.5 URD DBs
# It may also have some, but less, effect on 1.0.5+ DBs.
# Written by Thorwak
#
# This works for me, YMMV. Use at your own risk.
#
# If you think you have the RAM for it, feel free to increase $MAXRECORDS below.
# Otherwise it's better to run several times. Overlapping previous ranges is fine.
#
# Please note that you have to run a optimize on the table when you're done to actually
# make the DB files shrink.
#
# TODO: Fix memory handling
#

use DBI;

$MYSQLHOST  = "localhost";
$DBLOGIN    = "urd_user";
$DBPASSW    = "xxxx";
$DB         = "urddb";

$MAXRECORDS = 1000000; #We may run out of memory if trying to process too many rows at once. Speed is also affected.

# Print usage info of started without correct parameters
if (($#ARGV ne 2) or (not $ARGV[0] > 0) or (not $ARGV[1] > 0) or (not $ARGV[2] > 0)) {
  print "\nUsage: $0 <group ID> <start record ID #> <end record id>\n\n";
  exit -1;
}

# Friendlynames on args
$groupid = $ARGV[0];
$startid = $ARGV[1];
$stopid  = $ARGV[2];

# Sane checking
if (($stopid - $startid) > $MAXRECORDS) {
  print("\nError: Too many rows selected at once (max is currently set to $MAXRECORDS). Aborting.\n");
  exit -1
}

if ($startid > $stopid) {
  print("\nError: End record ID is lower than start record ID. Check your parameters! Aborting.\n");
  exit -1;
}

# Connect to DB
$dbh = DBI->connect("DBI:mysql:$DB:$MYSQLHOST", "$DBLOGIN", "$DBPASSW", {RaiseError => 1, AutoCommit => 1})
  or die "ERROR: Can't connect to database. Error string: " . DBI->errstr;

# Get some info on the group (for user display)
$grouph = $dbh->prepare("SELECT name, postcount, active FROM groups WHERE ID = $groupid");
eval {
  $grouph->execute();
};
if ($@) { die "Error executing SELECT from DB, terminating. Error string : $@\n" };
($groupname, $grouppostcount, $active) = $grouph->fetchrow_array();
$grouph->finish();

if ($active == 0) {
  print("\nError: You are not subscribed to the specified group! (group ID $groupid, $groupname). Aborting.\n\n");
  exit -1
}

print ("\nGoing to clean reduntant data in table parts_$groupid ($groupname, total postcount = $grouppostcount)\n");
print ("Please wait ...\n");
# Init vars
$id = NULL;
$binaryid = '';
$fromname = '';
$subject = '';
$updatectr = 0;
$uniqctr = 0;
$blankctr = 0;

# Prepare and execute query to select lines in the range given from cli
$headersh = $dbh->prepare("SELECT ID, binaryID, subject, fromname FROM parts_$groupid
                           WHERE ID >= $startid AND ID <= $stopid
                           ORDER BY ID"); 
eval {
  $headersh->execute();
};
if ($@) { die "Error executing SELECT from DB, terminating. Error string : $@\n" };


# Prepare query to be used when blanking fromname and subject
$blankingh = $dbh->prepare("UPDATE parts_$groupid SET fromname = \"\", subject = \"\" WHERE ID = ?");

# Loop through headers
while (($id,$binaryid,$fromname,$subject) = $headersh->fetchrow_array()) {
  if (($fromname ne "") and ($subject ne "")) {    # Don't touch if there are empty field(s) already
    if (exists $binaryidlist{$binaryid}) {         # Do we have it already?
    # Blank out fromname and subject in DB
      eval {
        $blankingh->execute($id);
      };
      if ($@) { die "Error executing UPDATE to DB, terminating. This is probably not very good... Error string : $@\n" };
      $updatectr++
    } else {
#      print("Unique: $id $binaryid $fromname $subject\n");    # Debug
      $binaryidlist{$binaryid} = 1;  # Flag it found
      $uniqctr++;
    }
  } else {
    $blankctr++;
  }
}

# Clean up
$blankingh->finish();
$headersh->finish();
$dbh->disconnect();

print "\nDone. $updatectr DB updates, $uniqctr unique binaryIDs found and $blankctr already cleaned rows found.\n";
print "You probably want to do a \"OPTIMIZE TABLE parts_$groupid\" at this point (or after you're done with whole table).\n\n";

