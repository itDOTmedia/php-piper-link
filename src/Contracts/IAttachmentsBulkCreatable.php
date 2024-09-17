<?php
namespace Idm\PiperLink\Contracts;

interface IAttachmentsBulkCreatable
{
    /**
     * Create/upload one or attachments.
     *
     * @param array $documentFiles
     * @param array $arguments
     * @return \Generator Generates new attachment objects filled with resulting customData.
     */
    public function createAttachments(array $documentFiles, array $arguments = []): \Generator;
}