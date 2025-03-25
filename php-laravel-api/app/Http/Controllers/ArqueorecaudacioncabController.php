<?php 
namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Http\Requests\ArqueorecaudacioncabAddRequest;
use App\Http\Requests\ArqueorecaudacioncabEditRequest;
use App\Models\Arqueorecaudacioncab;
use Illuminate\Http\Request;
use Exception;
class ArqueorecaudacioncabController extends Controller
{
	

	/**
     * List table records
	 * @param  \Illuminate\Http\Request
     * @param string $fieldname //filter records by a table field
     * @param string $fieldvalue //filter value
     * @return \Illuminate\View\View
     */
	function index(Request $request, $fieldname = null , $fieldvalue = null){
		$query = Arqueorecaudacioncab::query();
		if($request->search){
			$search = trim($request->search);
			Arqueorecaudacioncab::search($query, $search);
		}
		$orderby = $request->orderby ?? "arqueorecaudacioncab.arqueorecid";
		$ordertype = $request->ordertype ?? "desc";
		$query->orderBy($orderby, $ordertype);
		if($fieldname){
			$query->where($fieldname , $fieldvalue); //filter by a single field name
		}
		$records = $this->paginate($query, Arqueorecaudacioncab::listFields());
		return $this->respond($records);
	}
	

	/**
     * Select table record by ID
	 * @param string $rec_id
     * @return \Illuminate\View\View
     */
	function view($rec_id = null){
		$query = Arqueorecaudacioncab::query();
		$record = $query->findOrFail($rec_id, Arqueorecaudacioncab::viewFields());
		return $this->respond($record);
	}
	

	/**
     * Save form record to the table
     * @return \Illuminate\Http\Response
     */
	function add(ArqueorecaudacioncabAddRequest $request){
		$modeldata = $request->validated();
		
		//save Arqueorecaudacioncab record
		$record = Arqueorecaudacioncab::create($modeldata);
		$rec_id = $record->arqueorecid;
		return $this->respond($record);
	}
	

	/**
     * Update table record with form data
	 * @param string $rec_id //select record by table primary key
     * @return \Illuminate\View\View;
     */
	function edit(ArqueorecaudacioncabEditRequest $request, $rec_id = null){
		$query = Arqueorecaudacioncab::query();
		$record = $query->findOrFail($rec_id, Arqueorecaudacioncab::editFields());
		if ($request->isMethod('post')) {
			$modeldata = $request->validated();
			$record->update($modeldata);
		}
		return $this->respond($record);
	}
	

	/**
     * Delete record from the database
	 * Support multi delete by separating record id by comma.
	 * @param  \Illuminate\Http\Request
	 * @param string $rec_id //can be separated by comma 
     * @return \Illuminate\Http\Response
     */
	function delete(Request $request, $rec_id = null){
		$arr_id = explode(",", $rec_id);
		$query = Arqueorecaudacioncab::query();
		$query->whereIn("arqueorecid", $arr_id);
		$query->delete();
		return $this->respond($arr_id);
	}
}
