<?php namespace October\Rain\Assetic\Filter;

use October\Rain\Assetic\Asset\AssetInterface;

/**
 * CssCacheBustingFilter
 *
 * @package october\assetic
 * @author Maximilian Reichel <info@phramz.com>
 */
class CssCacheBustingFilter extends BaseCssFilter
{
    private $version;
    private $format = '%s?%s';

    public function setVersion($version)
    {
        $this->version = $version;
    }

    public function setFormat($versionFormat)
    {
        $this->format = $versionFormat;
    }

    public function filterLoad(AssetInterface $asset)
    {
    }

    public function filterDump(AssetInterface $asset)
    {
        if (!$this->version) {
            return;
        }

        $version = $this->version;
        $format = $this->format;

        $asset->setContent($this->filterReferences(
            $asset->getContent(),
            function ($matches) use ($version, $format) {
                if (0 === strpos($matches['url'], 'data:')) {
                    return $matches[0];
                }

                return str_replace(
                    $matches['url'],
                    sprintf($format, $matches['url'], $version),
                    $matches[0]
                );
            }
        ));
    }
}
