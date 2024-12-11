<div @class(['row', 'w-100'])>
    <div @class(['col-9'])>
        <div @class(['card']) wire:ignore.self>
            <div @class(['card-body', 'card-dashboard'])>
                <h5 @class(['card-title',  'title-card-number'])>Historico despesas</h5>
                <div @class('chart-area-expenses') style="height: 260px; width: 100%;">
                    <div id="expensesHistory" wire:ignore.self></div>
                </div>
            </div>
        </div>
    </div>
</div>
