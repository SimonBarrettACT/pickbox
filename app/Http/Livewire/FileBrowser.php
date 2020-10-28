<?php

namespace App\Http\Livewire;

use App\Models\Thing;
use Livewire\Component;

class FileBrowser extends Component
{

    public $thing;

    public $ancestors;

    public $creatingNewFolder = false;

    public $newFolderState = [
        'name' => ''
    ];

    public $renamingThing;

    public $renamingThingState;

    public $showingFileUploadForm = false;

    public function renameThing() {
        $this->validate([
            'renamingThingState.name' => 'required|max:255'
        ]);

        Thing::forCurrentTeam()
            ->find($this->renamingThing)
            ->thingable
            ->update($this->renamingThingState);

        $this->thing = $this->thing->fresh();
        $this->renamingThing = null;

    }

    public function updatingRenamingThing($id)
    {
        if ($id === null) {
            return;
        }

        if ($thing = Thing::forCurrentTeam()->find($id)) {
            $this->renamingThingState = [
                'name' => $thing->thingable->name
            ];
        }

    }

    public function createFolder()
    {
        $this->validate([
            'newFolderState.name' => 'required|max:255'
        ]);

        $thing = $this->currentTeam->things()->make(['parent_id' => $this->thing->id]);
        $thing->thingable()->associate($this->currentTeam->folders()->create($this->newFolderState));
        $thing->save();

        $this->creatingNewFolder = false;
        $this->newFolderState = [
            'name' => ''
        ];

        $this->thing = $this->thing->fresh();

    }

    public function getCurrentTeamProperty()
    {
        return auth()->user()->currentTeam;
    }
    public function render()
    {
        return view('livewire.file-browser');
    }
}
