{foreach from=$results item="block"}
    <h1>À chaque pièce son mobilier</h1>
    <div class="category-row category-row_{$block.template} {$block.class}">
        {foreach from=$block.categories item="category"}
            <div class="category-item">
                <img  alt="{$category->name}" src="/img/c/{$category->id_category}.jpg"/>
                <h2>{$category->name}</h2>
            </div>
        {/foreach}
    </div>
{/foreach}