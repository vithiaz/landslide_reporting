<?php

namespace App\Http\Livewire;

use App\Models\TelegramUser;
use Illuminate\Support\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Rules\{Rule, RuleActions};
use PowerComponents\LivewirePowerGrid\Filters\Filter;
use PowerComponents\LivewirePowerGrid\Traits\ActionButton;
use PowerComponents\LivewirePowerGrid\{Button, Column, Exportable, Footer, Header, PowerGrid, PowerGridComponent, PowerGridColumns};

final class TelegramUserTable extends PowerGridComponent
{
    use ActionButton;

    // Listeners
    protected function getListeners()
    {
        return array_merge(
            parent::getListeners(), [
                'switchStatus',
                'deleteUser',
                'reloadTable',
            ]);
    }

    public function switchStatus($id) {
        $userId = $id['id'];

        $TelegramUser = TelegramUser::find($userId);
        
        if ($TelegramUser) {
            if ($TelegramUser->status == 'active') {
                $TelegramUser->status = 'inactive';
                $msg = ['info' => 'user dinonaktifkan'];
            } else {
                $TelegramUser->status = 'active';
                $msg = ['success' => 'user diaktifkan'];
            }
            $TelegramUser->save();
            
            $this->fillData();
            $this->dispatchBrowserEvent('display-message', $msg);
        }
    }

    public function deleteUser($id) {
        $userId = $id['id'];

        $TelegramUser = TelegramUser::find($userId);
        if ($TelegramUser) {
            $TelegramUser->delete();
            
            $this->fillData();
            $msg = ['info' => 'user dihapus'];
            $this->dispatchBrowserEvent('display-message', $msg);
        }
    }

    public function reloadTable() {
        $this->fillData();
    }




    public function setUp(): array
    {
        return [
            Header::make()->showSearchInput(),
            Footer::make()
                ->showPerPage(),
        ];
    }

    /*
    |--------------------------------------------------------------------------
    |  Datasource
    |--------------------------------------------------------------------------
    | Provides data to your Table using a Model or Collection
    |
    */

    /**
     * PowerGrid datasource.
     *
     * @return Builder<\App\Models\TelegramUser>
     */
    public function datasource(): Builder
    {
        return TelegramUser::where('is_bot', '=', false);
    }

    /*
    |--------------------------------------------------------------------------
    |  Relationship Search
    |--------------------------------------------------------------------------
    | Configure here relationships to be used by the Search and Table Filters.
    |
    */

    /**
     * Relationship search.
     *
     * @return array<string, array<int, string>>
     */
    public function relationSearch(): array
    {
        return [];
    }

    /*
    |--------------------------------------------------------------------------
    |  Add Column
    |--------------------------------------------------------------------------
    | Make Datasource fields available to be used as columns.
    | You can pass a closure to transform/modify the data.
    |
    | ❗ IMPORTANT: When using closures, you must escape any value coming from
    |    the database using the `e()` Laravel Helper function.
    |
    */
    public function addColumns(): PowerGridColumns
    {
        return PowerGrid::columns()
            ->addColumn('id')
            ->addColumn('username')
            ->addColumn('first_name')
            ->addColumn('status')
            ->addColumn('created_at')
            ->addColumn('created_at_formatted', fn (TelegramUser $model) => Carbon::parse($model->created_at)->format('d/m/Y H:i:s'));
    }

    /*
    |--------------------------------------------------------------------------
    |  Include Columns
    |--------------------------------------------------------------------------
    | Include the columns added columns, making them visible on the Table.
    | Each column can be configured with properties, filters, actions...
    |
    */

    /**
     * PowerGrid Columns.
     *
     * @return array<int, Column>
     */
    public function columns(): array
    {
        return [
            Column::make('Chat ID', 'id')
                ->searchable()
                ->sortable(),

            Column::make('Username', 'username')
                ->searchable(),

            Column::make('Nama Depan', 'first_name')
                ->searchable(),

            Column::make('Status', 'status')
                ->sortable()
                ->searchable(),

            Column::make('Created at', 'created_at')
                ->hidden(),
                
            Column::make('Tanggal', 'created_at_formatted', 'created_at')
                ->searchable()
                ->hidden()
        ];
    }

    /**
     * PowerGrid Filters.
     *
     * @return array<int, Filter>
     */
    public function filters(): array
    {
        return [
            // Filter::inputText('name'),
            // Filter::datepicker('created_at_formatted', 'created_at'),
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Actions Method
    |--------------------------------------------------------------------------
    | Enable the method below only if the Routes below are defined in your app.
    |
    */

    /**
     * PowerGrid TelegramUser Action Buttons.
     *
     * @return array<int, Button>
     */


    public function actions(): array
    {
       return [
           Button::make('switch', '<i class="fa-solid fa-toggle-on"></i>')
                ->class('btn btn-default-dark')
                ->emit('switchStatus', ['id' => 'id'])
                ,
           Button::make('delete', '<i class="fa-solid fa-trash"></i>')
                ->class('btn btn-default-red')
                ->emit('deleteUser', ['id' => 'id']),

        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Actions Rules
    |--------------------------------------------------------------------------
    | Enable the method below to configure Rules for your Table and Action Buttons.
    |
    */

    /**
     * PowerGrid TelegramUser Action Rules.
     *
     * @return array<int, RuleActions>
     */

    /*
    public function actionRules(): array
    {
       return [

           //Hide button edit for ID 1
            Rule::button('edit')
                ->when(fn($telegram-user) => $telegram-user->id === 1)
                ->hide(),
        ];
    }
    */
}
