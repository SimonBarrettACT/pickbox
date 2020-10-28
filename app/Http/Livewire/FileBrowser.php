<?php

namespace App\Http\Livewire;

use App\Models\Thing;
use Livewire\Component;
use Livewire\WithFileUploads;

class FileBrowser extends Component
{
    use WithFileUploads;

    public $query;

    public $upload;

    public $thing;

    public $ancestors;

    public $creatingNewFolder = false;

    public $newFolderState = [
        'name' => ''
    ];

    public $renamingThing;

    public $renamingThingState;

    public $showingFileUploadForm = false;

    public $confirmingThingDeletion;

    public function getResultsProperty()
    {
        if (strlen($this->query)) {
            return Thing::search($this->query)->where('team_id', $this->currentTeam->id)->get();
        }

        return $this->thing->children;
    }

    public function deleteThing()
    {
        Thing::forCurrentTeam()->find($this->confirmingThingDeletion)->delete();
        $this->confirmingThingDeletion = null;
        $this->thing = $this->thing->fresh();
    }


    public function updatedUpload($upload)
    {
        $thing = $this->currentTeam->things()->make(['parent_id' => $this->thing->id]);

        $thing->thingable()->associate(
            $this->currentTeam->files()->create([
                'name' => $upload->getClientOriginalName(),
                'size' => $upload->getSize(),
                'path' => $upload->storePublicly(
                    'files', [
                        'disk' => 'local'
                    ]
                )
            ])
        );

        $thing->save();

        $this->thing = $this->thing->fresh();

    }
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
