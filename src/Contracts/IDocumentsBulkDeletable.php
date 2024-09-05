<?php
namespace Idm\PiperLink\Contracts;

interface IDocumentsBulkDeletable
{
    /**
      * Deletes multiple documents by given ids.
      *
      * @param string[] $ids The array of documents ids to delete.
      * @param array $arguments
      * @return \Generator<Document|string> Generates deleted documents with ids on success or error strings on failure.
      */
    public function deleteDocuments(array $ids, array $arguments = []): \Generator;
}