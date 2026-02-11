<?php

namespace App\Livewire\Projects;

use App\Models\Project;
use Livewire\Component;

class ProjectIndex extends Component
{
public function render()
{
return view('livewire.projects.index', [
'projects' => Project::paginate(10)
]);
}
}
