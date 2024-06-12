<?php

namespace Xima\XmTools\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

/**
 * Class TtContent
 *
 * Model of tt_content
 */
class TtContent extends AbstractEntity
{
    protected string $header = '';

    protected string $sorting = '';

    protected string $contentType = '';

    protected string $piFlexform;

    protected string $listType;

    protected int $sysLanguageUid = 0;

    public function getHeader(): string
    {
        return $this->header;
    }

    public function setHeader(string $header): void
    {
        $this->header = $header;
    }

    public function getSorting(): string
    {
        return $this->sorting;
    }

    public function setSorting(string $sorting): void
    {
        $this->sorting = $sorting;
    }

    public function getContentType(): string
    {
        return $this->contentType;
    }

    public function setContentType(string $contentType): void
    {
        $this->contentType = $contentType;
    }

    public function getPiFlexform(): string
    {
        return $this->piFlexform;
    }

    public function getListType(): string
    {
        return $this->listType;
    }

    public function getSysLanguageUid(): int
    {
        return $this->sysLanguageUid;
    }

    public function setSysLanguageUid(int $sysLanguageUid): void
    {
        $this->sysLanguageUid = $sysLanguageUid;
    }
}
