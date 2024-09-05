<?php
namespace Idm\PiperLink\Contracts;

use Idm\PiperLink\Models\Document;

interface IDocumentsBulkCreateable
{
    /**
     * Create a bulk of new documents.
     *
     * @param Document[] $documents Array of documents to create.
     * @param array $arguments
     * @return \Generator<Document|string> Generates new documents on success or error strings on failure.
     */
    public function createDocuments(array $documents, array $arguments = []): \Generator;
}