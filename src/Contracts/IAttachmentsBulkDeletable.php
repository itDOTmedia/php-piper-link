<?php
namespace Idm\PiperLink\Contracts;

interface IAttachmentsBulkDeletable
{
    /**
      * Deletes multiple attachments by given ids.
      *
      * @param string[] $ids The array of attachments ids to delete.
      * @param array $arguments
      * @return \Generator<Attachment|string> Generates deleted attachments with ids on success or error strings on failure.
      */
    public function deleteAttachments(array $ids, array $arguments = []): \Generator;
}