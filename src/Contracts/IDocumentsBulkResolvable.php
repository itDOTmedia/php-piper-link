<?php
namespace Idm\PiperLink\Contracts;

use Idm\PiperLink\Models\Document;

/**
 * Try to find documents ids by given properties.
 * If the the id is already set, simply return the document itself.
 * The document Id may only be set if the document can be clearly identified!
 */
interface IDocumentsBulkResolvable
{
    /**
     * Returns the given documents filled with the 'id' property.
     *
     * @param Document[]|null $documents The array of documents to find the ids for.
     * @param array $arguments
     * @return \Generator<Document|null> Generates document objects.
     */
    public function resolveDocuments(array $documents, array $arguments = []): \Generator;
}