<?php

namespace App\Http\Controllers\Community;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Community\Project;
use App\Models\Community\CharitableInstitution;
use App\Models\Community\SpecificAim;
use App\Models\Community\ProjectActivities;
use App\Models\Community\StudentParticipant;
use App\Models\Community\TeacherParticipant;
use App\Models\Community\Observation;
use App\Models\Ignug\Career;
use App\Models\Ignug\Catalogue;
use App\Models\Ignug\Image;
use Illuminate\Support\Facades\DB;

class projectsController extends Controller
{
  public function show(){
    
    $project_env=array();
    $project_last=Project::select('id')->get()->last();
    for ($i=1; $i <= $project_last->id; $i++) { 
      $project=Project::find($i);
      $assigned_line=Project::find($i)->assigned_line;
      $fraquencyOfActivity=Project::find($i)->fraquency;
      $status=Project::find($i)->status;
      $project["assigned_line_id"]=$assigned_line->name;
      $project["fraquency_id"]=$fraquencyOfActivity->name;
      $project["status_id"]=$status->name;
      $project["charitable_institution_id"]=Project::find($i)->first()->CharitableInstitution;
      $project_env[]=$project;
    }
    return $project_env;
 }

 public function create(Request $request){
  
  //CharatableInstitution
   $CharitableInstitution= new CharitableInstitution; 
   $CharitableInstitution->state_id=1;
   $CharitableInstitution->ruc=  $request->ruc;
   $CharitableInstitution->name= $request->name_institution;
   $CharitableInstitution->location_id= $request->location_id;
   $CharitableInstitution->indirect_beneficiaries=$request->indirect_beneficiaries;
   $CharitableInstitution->legal_representative_name=$request->legal_representative_name;
   $CharitableInstitution->legal_representative_lastname=$request->legal_representative_lastname;
   $CharitableInstitution->legal_representative_identification=$request->legal_representative_identification;
   $CharitableInstitution->project_post_charge=$request->project_post_charge;
   $CharitableInstitution->direct_beneficiaries=$request->direct_beneficiaries;
   $CharitableInstitution->save();
   //fk Search
   $fkCharitableInstitution=CharitableInstitution::where('ruc', $request->ruc)->first();
   //project    
   $Project=new Project;
   $Project->charitable_institution_id=$fkCharitableInstitution->id;                 
  // $Project->academi_period_id=$fkacademiPreriod->id;
   $Project->career_id=$request->career_id;
   $Project->assigned_line_id=$request->assigned_line_id;
   $Project->code=$request->code;
   $Project->name=$request->project_name;
   $Project->status_id= $request->status_id;
   $Project->state_id=1;
   $Project->field=$request->field;
   $Project->aim=$request->aim;
   $Project->fraquency_id=$request->fraquency_id;
   $Project->cycle=$request->cycle;
   $Project->location_id=$request->location_project; //localitation'
   $Project->lead_time=$request->lead_time;
   $Project->delivery_date=$request->delivery_date;// tiempo
   $Project->start_date=$request->start_date;// tiempo
   $Project->end_date=$request->end_date;//tienmpo
   $Project->description=$request->description;
   $Project->coordinator_name=$request->coordinator_name;
   $Project->coordinator_lastname=$request->coordinator_lastname;
   $Project->coordinator_postition=$request->coordinator_postition;
   $Project->coordinator_funtion=$request->coordinator_funtion;
   $Project->introduction=$request->introduction;
   $Project->situational_analysis=$request->situational_analysis;
   $Project->foundamentation=$request->foundamentation;
   $Project->justification=$request->justification;
   $Project->bibliografia=$request->bibliografia;
   $Project->save();
  
   //fk Project searh
   $fkProject=Project::where('code',$request->code)->first("id");
   //SpecificAim
   $projectcontrol= new projectsController;
   
   for($con=0;$con<count($request->type_id_specific);$con++){
    $fkaims=$request->parent_code_id[$con] <> null ? SpecificAim::where('description',$request->parent_code_id[$con])->first("id") : (object) array("id"=>null);
    $projectcontrol->aimsCreate(
      $fkProject->id,
      $request->type_id_specific[$con],
      $request->description_aims[$con],
      $request->indicator[$con],
      $request->verifications[$con],
      $fkaims->id
    );  
   }
  //ProjectActivities
    for($con=0;$con<count($request->type_id_activities);$con++){
    $projectcontrol->projectActivitiesCreate($fkProject->id,$request->type_id_activities[$con],$request->detail_activities[$con]);
   } 
  //img 
  /*$filePath = $request->logo->storeAs('charitable_institution',  $fkCharitableInstitution->name. '.png', 'public');
  $images= new Image;
  $images->code=$fkCharitableInstitution->ruc;
  $images->name=$fkCharitableInstitution->name;
  $images->description='Este es para el uso de los pdf de vinculacion';
  $images->uri=$filePath;
  $images->type=Image::AVATAR_TYPE;
  $images->state_id=1;
  $images->save(); */


   return true; 
  }


  public function aimsCreate($id_project,$type_id,$description,$indicator,array $verifications,$parent_code_id){
    $SpecificAim = new SpecificAim;
    $SpecificAim->state_id=1;
    $SpecificAim->project_id=$id_project;
    $SpecificAim->indicator=$indicator;
    $SpecificAim->verifications=$verifications;
    $SpecificAim->description=$description;
    $SpecificAim->type_id=$type_id;
    $SpecificAim->parent_code_id=$parent_code_id;
    $SpecificAim->save();
  }
  public function projectActivitiesCreate($id_project,$type_id,$detail){
    $ProjectActivities= new ProjectActivities;
    $ProjectActivities->state_id=1;
    $ProjectActivities->project_id=$id_project;
    $ProjectActivities->type_id=$type_id;
    $ProjectActivities->$detail;
    $ProjectActivities->save();
  }

  public function studentParticipantCreate($id_project,$id_student,$funtionStudent){
    $Student= new StudentParticipant;
    $Student->state_id=1;
    $Student->student_id=$id_student;
    $Student->project_id=$id_project;
    $Student->funtion_id=$funtionStudent;
    $Student->save();
  }
  public function teacherParticipantCreate($id_project,$teacher_id,$workHours,$funtionTeacher){
    $Teacher=new TeacherParticipant;
    $Teacher->state_id=1;
    $Teacher->teacher_id=$id_project;
    $Teacher->project_id=$teacher_id;
    $Teacher->workHours=$workHours;
    $Teacher->funtion_id=$funtionTeacher;
    $Teacher->save();
  }

  public function destroy($id){
    if(!!ProjectActivities::where('project_id',$id)->get()){
      DB::connection('pgsql-community')->table('project_activities')->where('project_id', $id)->delete();
    } 
    if(!!StudentParticipant::where('project_id',$id)->get()){ 
      DB::connection('pgsql-community')->table('student_participants')->where('project_id', $id)->delete();
    }  
    if(!!TeacherParticipant::where('project_id',$id)->get()){
      DB::connection('pgsql-community')->table('teacher_participants')->where('project_id', $id)->delete();
    }
    if(!!SpecificAim::where('project_id',$id)->get()){
      DB::connection('pgsql-community')->table('specific_aims')->where('project_id', $id)->delete();
    }
    /* if(!!Observation::where('project_id',$id)->get()){ 
      DB::connection('pgsql-community')->table('observations')->where('project_id', $id)->delete();
    } */
    if(!!Project::find($id)->get()){
      DB::connection('pgsql-community')->table('projects')->where('id', $id)->delete();
    }else{
      return "No existe el proyecto";
    }
      return "proyecto eliminado";
  }
  public function edit($id){
    
    $project=Project::where("id",$id)->first();
    $assigned_line=Project::find($id)->assigned_line;
    $fraquencyOfActivity=Project::find($id)->fraquency;
    $status=Project::find($id)->status;
   //sustitucion de datos Carrera modelo fk belongto Catalogue
   /*
   
   */
    $project["career_id"]=Career::where('careers.id',$project->career_id)
    ->join('catalogues','careers.modality_id','=','catalogues.id')
    ->first(["careers.id","careers.name","catalogues.name as modality"]);
    $project["assigned_line_id"]=$assigned_line->name;
    $project["fraquency_id"]=$fraquencyOfActivity->name;
    $project["status_id"]=$status->name;
    $project["charitable_institution_id"]=Project::find($id)->first()->CharitableInstitution; //CharitableInstitution::where("id",$project->charitable_institution_id)->first();
   //nuevos datos de otras tablas 
    $studentParticipant=StudentParticipant::where("project_id",$id)->get();
    $teacherParticipant=TeacherParticipant::where("project_id",$id)->get();
    $specificAim=SpecificAim::where("project_id",$id)->get();
    $projectActivities=ProjectActivities::where("project_id",$id)->get();
    //$observation=Observation::where('project_id',$id)->get();
    $pdf= array(
      'project'=>$project,
      'studentParticipant'=>$studentParticipant,
      'teacherParticipant'=>$teacherParticipant,
      'specificAim'=>$specificAim,
      'projectActivities'=>$projectActivities,
      //'observation'=>$observation,

    );
    
    
    return $pdf;
    
   }

  public function creador(Request $request){
    $vista=Catalogue::all();
    return $vista;
  }

}