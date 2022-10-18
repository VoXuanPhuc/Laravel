<?php

namespace Encoda\EDocs\Traits;

use Encoda\Core\Helpers\ObjectHelper;
use Encoda\EDocs\Models\Document;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use PhpParser\Comment\Doc;

trait InteractsWithDocument
{
    public array $documentCollections = [];

    /**
     * @return mixed
     */
    public function documents(): MorphMany
    {
        return $this->morphMany(Document::class, 'model');
    }

    /**
     * @param        $target
     * @param string $type
     *
     * @return void
     */
    public function addDocument($target, string $type = 'default'): void
    {
        $documents = ObjectHelper::toCollection($target);
        $documents->each(function ($item) use ($type) {
            if(is_string($item) || is_int($item)){
                $document = Document::find($item);
            }else{
                $document = $item;
            }
            if($document instanceof Document)
            {
                $document->type = $type;
                $document->model()->associate($this);
                $document->save();
            }
        });
    }

    /**
     * @param mixed  $target
     * @param string $type
     *
     * @return void
     */
    public function syncDocument(mixed $target, string $type = 'default'): void
    {
        $this->clearDocumentCollection($type);
        $this->addDocument($target, $type);
    }

    /**
     * @param string $type
     *
     * @return Collection
     */
    public function getDocuments(string $type ='default'): Collection
    {
        return $this->documents->where('type', $type);
    }

    /**
     * @param string $type
     *
     * @return MorphMany|mixed
     */
    public function getDocumentsQuery(string $type = 'default'): mixed
    {
        return $this->documents()->where('type', $type);
    }

    /**
     * @param string|null $type
     *
     * @return void
     */
    public function clearDocumentCollection(?string $type)
    {
        $documents = $this->documents()->when($type, function ($query, $type) {
            $query->where('type', $type);
        })->get();
        $documents->each(function ($document){
            $document->delete();
        });
    }

}
