<div class="panel">
    <h3>{l s='Product Managers' mod='productmanager'}</h3>
    <table class="table">
        <thead>
            <tr>
                <th>{l s='Product ID' mod='productmanager'}</th>
                <th>{l s='Manager Name' mod='productmanager'}</th>
                <th>{l s='Manager Phone' mod='productmanager'}</th>
                <th>{l s='Manager Email' mod='productmanager'}</th>
                <th>{l s='Actions' mod='productmanager'}</th>
            </tr>
        </thead>
        <tbody>
            {foreach from=$managers item=manager}
            <tr>
                <td>{$manager.id_product}</td>
                <td>{$manager.manager_name}</td>
                <td>{$manager.manager_phone}</td>
                <td>{$manager.manager_email}</td>
                <td>
                    <a href="{$link->getAdminLink('AdminProductManager')}&updateproduct_manager&id_product={$manager.id_product}" class="btn btn-primary">{l s='Edit' mod='productmanager'}</a>
                </td>
            </tr>
            {/foreach}
        </tbody>
    </table>
</div>
