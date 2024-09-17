<?php
namespace Idm\PiperLink\Contracts;

use Idm\PiperLink\Models\Attachment;

interface IAttachmentsBulkFetchable
{
    /**
     * Fetch a list of attachments.
     *
     * @param string[]|null $ids The array of attachments ids to fetch or null to fetch all attachments.
     * @param array $arguments
     * @return \Generator<Attachment|null> Generate attachments objects on success or null entries on error.
     */
    public function getAttachments(?array $ids, array $arguments = []): \Generator;
}