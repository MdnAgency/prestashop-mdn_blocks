{foreach from=$results item="block"}
    <img src="/img/bloc_image/{$block.image}" alt="{$block.image_alt}" class="{$block.class}"/>
    <div class="image-block">
        <p>{$block.image_alt}</p>
    </div>
{/foreach}