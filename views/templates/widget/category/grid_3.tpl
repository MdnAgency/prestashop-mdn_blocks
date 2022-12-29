{foreach from=$results item="block"}
    <div class="category-row category-row_{$block.template} {$block.class}">
        {foreach from=$block.categories item="category"}
            <a href="{url entity='category' id=$category->id_category}" class="category-item">
                <img  alt="{$category->name}" src="/img/c/{$category->id_category}.jpg"/>
                <h2>{if $category->id_category == 3}Le {/if}{if $category->id_category == 14 || $category->id_category == 23}La {/if}{$category->name}</h2>
            </a>
        {/foreach}
    </div>
{/foreach}