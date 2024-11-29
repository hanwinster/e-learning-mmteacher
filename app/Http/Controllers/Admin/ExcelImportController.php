<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Imports\UsersImport;
use App\Imports\ResourcesImport;
use Maatwebsite\Excel\Importer;

class ExcelImportController extends Controller
{
    private $importer;

    public function __construct(Importer $importer)
    {
        $this->importer = $importer;
    }
    
    public function importUsers()
    {
        return $this->importer->import(new UsersImport, request()->file('import_excel_file')); //'users.xlsx');
    }
	
	public function importResources()
    {
        return $this->importer->import(new ResourcesImport, request()->file('your_file'));
    }
	
}
