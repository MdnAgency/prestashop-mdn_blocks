{foreach from=$results item="block"}
    <div class="category-row category-row_{$block.template} {$block.class}">
        <div class="special-block special-block_blue">
            <img alt="Logo de MeublezVous.com" src="/img/logo-partial.svg"/>
            <h2 class="white">Des collections pour tous les styles</h2>
        </div>
        {foreach from=$block.categories item="category"}
            <a href="{url entity='category' id=$category->id_category}" class="category-item">
                <img  alt="{$category->name}" src="/img/c/{$category->id_category}.jpg"/>
                <div class="mov"><span class="text-uppercase font-weight-semibold">{$category->name}</span><br/><span class="text-md font-weight-normal">{$category->sub_title}</span></div>
            </a>
        {/foreach}
        <div class="special-block special-block_blue">
            <img alt="Logo de MeublezVous.com" src="/img/logo-partial.svg"/>
        </div>
    </div>
{/foreach}