<?php

namespace App\Http\Controllers\Community;

use App\Http\Controllers\Controller;
use App\Models\Community\Project;
use Illuminate\Http\Request;
use App\Models\Ignug\Career;
use App\Models\Ignug\Catalogue;



class combosController extends Controller
{
  public function show(){
   // $academiPreriod=AcademiPeriod::all("nombre","id");//esta tabla por el momento va hacer creada por el ignug 
    $career=Career::join('catalogues','careers.modality_id','=','catalogues.id')
    ->get(["careers.name","careers.id","catalogues.name as modality"]);
    $mode=Catalogue::where('type','career_modality')->get(["name","id"]);
    $meansOfVerification=Catalogue::where('type','means_verification')->get(["name","id"]);
    $fraquencyOfActivity=Catalogue::where('type','fraquency_activity')->get(["name","id"]);
    $assignedLine=Catalogue::where('type','assigned_line')->get(["name","id"]);
    $linkageAxes=Catalogue::where('type','linkage_axes')->get(["name",'id']);//ejes de vinculacion
    $bondingActivities=Catalogue::where('type','bonding_activities')->get(["name","id"]);//Actividad de vinculación
    $researchAreas=Catalogue::where('type','research_areas')->get(["name","id"]);//rea de investigacion
    $aims=Catalogue::where('type','aims')->get(["name","id"]);
    $funtionTeacher=Catalogue::where('type','funtion_vinculacion')->get(["name","id"]);
    $status=Catalogue::where('type','status_vinculacion')->get(["name","id"]);
    $combos=array(
        //"academiPreriod"=>$academiPreriod,
        "career"=>$career,
        "mode"=>$mode,
        "meansOfVerification"=>$meansOfVerification,
        "assignedLine"=>$assignedLine,
        "linkageAxes"=>$linkageAxes,
        "bondingActivities"=>$bondingActivities,
        "fraquencyOfActivity"=>$fraquencyOfActivity,
        "research_areas"=>$researchAreas,
        "aims"=>$aims,
        "teacher_funtion"=>$funtionTeacher,
        "status"=>$status,
        //"Catalogue"=>$catalogue,
       
      );
    return $combos;
 }
 public function create(Request $request){
    $value=Catalogue::where('type',$request->type.'_vinculacion')->count();
    $catalogue= new Catalogue;
    $catalogue->code =$value+1;
    $catalogue->name = $request->name;
    $catalogue->type = $request->type->values."_vinculacion";//revisar
    $catalogue->state_id=1 ;
    $catalogue->save();
    return Catalogue::where('type',$request->type.'_vinculacion')->get();
 }

}