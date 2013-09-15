Subject: URD Download status update
# %u URL of URD
# %t timestamp
# %A Account user name
# %f Full name of the user
# %s status text; format %s{<STATUS_CODE>:<text>|<STATUS_CODE>:<text>|<STATUS_CODE>:<text>|...}

Hello %f,


Your download '%d' %s{4:is complete|9:failed to unrar properly|10:failed the checksum test|11:could not be repaired by par2|12:failed to download correctly|5:was cancelled|254:was cancelled because it was encrypted|*:is in an unknown state. This should not happen}.

See %uhtml/transfers.php for more info.


The URD administrator
