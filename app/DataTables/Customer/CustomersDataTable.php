<?php

namespace App\DataTables\Customer;

use App\Models\Customer;
use App\User;
use Yajra\DataTables\Services\DataTable;

class CustomersDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        return datatables($query)
            ->addIndexColumn()
            ->addColumn('action',function ($data){

                $showUrl = route('admin.customer.show', $data->id);
                $editUrl = route('admin.customer.edit', $data->id);
                return "<a class='waves-effect btn btn-success' data-value='$data->id' href='$showUrl'><i class='material-icons'>visibility</i></a> 
                        <a class='waves-effect btn btn-primary' data-value='$data->id' href='$editUrl'><i class='material-icons'>edit</i></a>
                        <button class='waves-effect btn deepPink-bgcolor delete' data-value='$data->id' ><i class='material-icons'>delete</i></button>";
            })
            ->setRowClass('gradeX');
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\User $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Customer $model)
    {
        return $model->newQuery()->select('id', 'name','phone','address', 'created_at', 'updated_at');
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->addAction(['width' => '80px'])
                    ->parameters($this->getBuilderParameters());
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns(): array
    {
        return [
            [
                'data' => 'name',
                'name' => 'name',
                'title' => 'Customer Name',
                'searchable' => true,
                'visible' => true,
                'orderable' => true
            ],
            [
                'data' => 'phone',
                'name' => 'phone',
                'title' => 'Phone Number',
                'searchable' => true,
                'visible' => true,
                'orderable' => true
            ],
            [
                'data' => 'address',
                'name' => 'address',
                'title' => 'Address',
                'searchable' => true,
                'visible' => true,
                'orderable' => true
            ],


        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'Customer/Customer_' . date('YmdHis');
    }
}