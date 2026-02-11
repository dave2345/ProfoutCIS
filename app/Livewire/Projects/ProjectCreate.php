<?php

namespace App\Livewire\Projects;

use App\Models\Project;
use Livewire\Component;

class ProjectCreate extends Component
{
public $name, $description;


protected $rules = ['name' => 'required'];


public function save()
{
$this->validate();
Project

::create(['name'=>$this->name,'description'=>$this->description,'created_by'=>auth()->id()]);
return redirect()->route('projects.index');
}
}
