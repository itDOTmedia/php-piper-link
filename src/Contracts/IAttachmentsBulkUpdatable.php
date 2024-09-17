<?php
namespace Idm\PiperLink\Contracts;

use Idm\PiperLink\Models\Attachment;

interface IAttachmentsBulkUpdatable
{
    /**
     * Updates a bulk of attachments.
     *
     * @param Attachment[] $attachments Array of attachments to update.
     * @param array $arguments
     * @return \Generator<Attachment|string> Generates updated attachments on success or error strings on failure.
     */
    public function updateAttachments(array $attachments, array $arguments = []): \Generator;
}