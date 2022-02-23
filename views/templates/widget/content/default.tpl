{foreach from=$results item="block"}
    <div class="content-block {$block.class}">
        {$block.content nofilter}
    </div>
{/foreach}