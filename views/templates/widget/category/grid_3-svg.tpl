{foreach from=$results item="block"}
    <div class="category-row category-row_{$block.template} {$block.class}">
        {foreach from=$block.categories item="category"}
            <a href="{url entity='category' id=$category->id_category}" class="category-item">
                <img  alt="{$category->name}" src="/img/c/{$category->id_category}.jpg"/>
                <h2><img  alt="{$category->name}" src="/img/theming/{$category->icon}">{$category->name}</h2>
            </a>
        {/foreach}
    </div>
{/foreach}