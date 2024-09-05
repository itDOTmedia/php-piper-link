<?php
namespace Idm\PiperLink\Contracts;

use Idm\PiperLink\Models\Document;

interface IDocumentsBulkUpdatable
{
    /**
     * Updates a bulk of documents.
     *
     * @param Document[] $documents Array of documents to update.
     * @param array $arguments
     * @return \Generator<Document|string> Generates updated documents on success or error strings on failure.
     */
    public function updateDocuments(array $documents, array $arguments = []): \Generator;
}