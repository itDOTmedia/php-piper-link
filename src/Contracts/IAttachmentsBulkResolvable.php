<?php
namespace Idm\PiperLink\Contracts;

use Idm\PiperLink\Models\Attachment;

/**
 * Try to find attachment ids by given properties.
 * If the the id is already set, simply return the attachment itself.
 * The attachment Id may only be set if the attachment can be clearly identified!
 */
interface IAttachmentsBulkResolvable
{
    /**
     * Returns the given attachments filled with the 'id' property.
     *
     * @param Attachment[]|null $attachments The array of attachments to find the ids for.
     * @param array $arguments
     * @return \Generator<Attachment|null> Generates document objects.
     */
    public function resolveAttachments(array $attachments, array $arguments = []): \Generator;
}