<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;

class UniqueInsensitive implements Rule
{
    /**
     * The table to run the query against.
     *
     * @var string
     */
    protected $table;

    /**
     * The column to check on.
     *
     * @var string
     */
    protected $column;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($table, $column = 'NULL')
    {
        $this->table = $table;
        $this->column = $column;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $column = $attribute;
        if (! $this->column) {
            $column = $this->column;
        }
        $count = DB::table($this->table)->whereRaw("LOWER($column) = ?", [mb_strtolower($value)])->count();

        return $count == 0;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Cet email est déjà pris.';
    }
}
