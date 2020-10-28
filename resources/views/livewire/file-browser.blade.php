<div>
    <div class="flex flex-wrap items-center justify-between mb-4">
        <div class="flex-grow md:mr-3 mt-4 md:mt-0 w-full md:w-auto order-3 md:order-1">
            <!--suppress HtmlFormInputWithoutLabel -->
            <input type="search" placeholder="Search files and folders"
                   class="w-full px-3 h-12 border-2 rounded-lg">
        </div>
        <div class="order-2">
            <div>
                <button class="bg-gray-200 px-6 h-12 rounded-lg mr-2" wire:click="$set('creatingNewFolder', true)">
                    {{ __('New folder') }}
                </button>
                <button class="bg-blue-500 text-white font-bold px-6 h-12 rounded-lg mr-2" wire:click="$set('showingFileUploadForm', true)">
                    {{ __('Upload files') }}
                </button>
            </div>

        </div>
    </div>

    <div class="border-2 border-grey-200 rounded-lg">
        <div class="py-2 px-3">

            <div class="flex items-center">
                @foreach ($ancestors as $ancestor)
                    <a href="{{ route('files', ['uuid' => $ancestor->uuid]) }}"
                       class="font-bold text-gray-400">{{ $ancestor->thingable->name }}</a>
                    @if (!$loop->last)
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                             class="text-gray-300 w-5 h-5 mx-1">
                            <path fill-rule="evenodd"
                                  d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                  clip-rule="evenodd"/>
                        </svg>
                    @endif
                @endforeach

            </div>

        </div>

        <div class="overflow-auto">
            <table class="w-full">
                <thead class="bg-gray-100">
                <tr>
                    <th class="text-left py-2 px-3">{{ __('Name') }}</th>
                    <th class="text-left py-2 px-3 w-2/12">{{ __('Size') }}</th>
                    <th class="text-left py-2 px-3 w-2/12">{{ __('Created') }}</th>
                    <th class="p-2 w-2/12"></th>
                </tr>
                </thead>

                <tbody>

                @if ($creatingNewFolder)
                    <tr class="border-gray-100 border-b-2 hover:bg-gray-100">
                        <td class="p-3">
                            <form class="flex items-center" wire:submit.prevent="createFolder">
                                <input type="text" name="" id=""
                                       class="w-full px-3 h-10 border-2 border-gray-200 rounded-lg mr-2"
                                       wire:model.defer="newFolderState.name"
                                />
                                <button type="submit" class="bg-blue-600 text-white px-6 h-10 rounded-lg mr-2">
                                    {{ __('Create') }}
                                </button>
                                <button wire:click="$set('creatingNewFolder', false)"
                                        class="bg-gray-200 px-6 h-10 rounded-lg mr-2">
                                    {{ __('Cancel') }}
                                </button>
                            </form>
                        </td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                @endif

                @foreach ($thing->children as $child)
                    <tr class="border-gray100 @if (!$loop->last)border-b @endif hover:bg-gray-100">
                        <td class="py-2 px-3 flex items-center">
                            @if ($child->thingable_type === 'file')
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                     stroke="currentColor" class="w-6 h-6 text-blue-600">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                </svg>
                            @endif

                            @if ($child->thingable_type === 'folder')
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                     stroke="currentColor" class="w-6 h-6 text-blue-600">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                                </svg>
                            @endif

                            @if ($renamingThing === $child->id)

                                    <form class="flex items-center ml-2 flex-grow" wire:submit.prevent="renameThing">
                                        <input type="text" name="" id=""
                                               class="w-full px-3 h-10 border-2 border-gray-200 rounded-lg mr-2"
                                               wire:model.defer="renamingThingState.name"
                                        />
                                        <button type="submit" class="bg-blue-600 text-white px-6 h-10 rounded-lg mr-2">
                                            {{ __('Rename') }}
                                        </button>
                                        <button wire:click="$set('renamingThing', null)"
                                                class="bg-gray-200 px-6 h-10 rounded-lg mr-2">
                                            {{ __('Cancel') }}
                                        </button>
                                    </form>

                            @else

                                @if ($child->thingable_type === 'folder')
                                    <a href="{{ route('files', ['uuid' => $child->uuid]) }}"
                                       class="p-2 font-bold text-blue-600 flex-grow">
                                        {{ $child->thingable->name }}
                                    </a>
                                @endif

                                @if ($child->thingable_type === 'file')
                                    <a href="" class="p-2 font-bold text-blue-600 flex-grow">
                                        {{ $child->thingable->name }}
                                    </a>
                                @endif

                            @endif

                        </td>
                        <td class="py-2 px-3">
                            @if ($child->thingable_type === 'file')
                            {{ $child->thingable->size }}
                            @else
                            &mdash;
                            @endif
                        </td>
                        <td class="py-2 px-3">
                            {{ $child->created_at }}
                        </td>
                        <td class="py-2 px-3">
                            <div class="flex justify-end items-center">
                                <ul class="flex items-center">
                                    <li class="mr-4">
                                        <button class="text-gray-400 font-bold" wire:click="$set('renamingThing', {{ $child->id }})">
                                            {{ __('Rename') }}
                                        </button>
                                    </li>
                                    <li>
                                        <button class="text-red-400 font-bold">
                                            {{ __('Delete') }}
                                        </button>
                                    </li>
                                </ul>
                            </div>

                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        @if ($thing->children->count() === 0)
            <div class="p-3 text-grey-700">
                {{ __('This folder is empty') }}
            </div>
        @endif

        <x-jet-modal wire:model="showingFileUploadForm">
            <div wire:ignore
                 class="m-3 border-dashed border-2"
                 x-data="{
                 initFilepond () {
                        const pond = FilePond.create(this.$refs.filepond)
                    }
                 }"
                 x-init="initFilepond"
            >
                <div>
                    <input type="file" x-ref="filepond" multiple>
                </div>
            </div>
        </x-jet-modal>

    </div>
</div>
