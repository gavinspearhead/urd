{strip}
{if $__show_time neq ''}
<div class="xxsmall right">
    {capture assign=time_b}{php}echo microtime(true);{/php}{/capture}
    {math equation="x-y" x=$time_b y=$time_a format="%.4f"}s</div>
    </div>
{/if}
</div>
</div>

<script type="text/javascript">
update_quick_status();
update_disk_status();
</script>
</div>
{/strip}
</body>
<!-- URD v{$VERSION} -->
</html>
