<?php
namespace Idm\PiperLink\Contracts;

use Idm\PiperLink\Models\Document;

interface IDocumentsBulkFetchable
{
    /**
     * Fetch a list of documents.
     *
     * @param string[]|null $ids The array of documents ids to fetch or null to fetch all documents.
     * @param array $arguments
     * @return \Generator<Document|null> Generate documents objects on success or null entries on error.
     */
    public function getDocuments(?array $ids, array $arguments = []): \Generator;
}