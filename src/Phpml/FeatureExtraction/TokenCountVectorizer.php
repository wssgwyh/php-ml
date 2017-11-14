<?php

declare(strict_types=1);

namespace Phpml\FeatureExtraction;

use Phpml\Tokenization\Tokenizer;
use Phpml\Transformer;

class TokenCountVectorizer implements Transformer
{
    /**
     * @var Tokenizer
     */
    private $tokenizer;

    /**
     * @var StopWords
     */
    private $stopWords;

    /**
     * @var float
     */
    private $minDF;

    /**
     * @var array
     */
    private $vocabulary;

    /**
     * @var array
     */
    private $frequencies;

    public function __construct(Tokenizer $tokenizer, StopWords $stopWords = null, float $minDF = 0.0)
    {
        $this->tokenizer = $tokenizer;
        $this->stopWords = $stopWords;
        $this->minDF = $minDF;

        $this->vocabulary = [];
        $this->frequencies = [];
    }

    public function fit(array $samples)
    {
        $this->buildVocabulary($samples);
    }

    public function transform(array &$samples)
    {
        foreach ($samples as &$sample) {
            $this->transformSample($sample);
        }

        $this->checkDocumentFrequency($samples);
    }

    public function getVocabulary() : array
    {
        return array_flip($this->vocabulary);
    }

    private function buildVocabulary(array &$samples)
    {
        foreach ($samples as $index => $sample) {
            $tokens = $this->tokenizer->tokenize($sample);
            foreach ($tokens as $token) {
                $this->addTokenToVocabulary($token);
            }
        }
    }

    private function transformSample(string &$sample)
    {
        $counts = [];
        $tokens = $this->tokenizer->tokenize($sample);

        foreach ($tokens as $token) {
            $index = $this->getTokenIndex($token);
            if (false !== $index) {
                $this->updateFrequency($token);
                if (!isset($counts[$index])) {
                    $counts[$index] = 0;
                }

                ++$counts[$index];
            }
        }

        foreach ($this->vocabulary as $index) {
            if (!isset($counts[$index])) {
                $counts[$index] = 0;
            }
        }

        ksort($counts);

        $sample = $counts;
    }

    /**
     * @return int|bool
     */
    private function getTokenIndex(string $token)
    {
        if ($this->isStopWord($token)) {
            return false;
        }

        return $this->vocabulary[$token] ?? false;
    }

    private function addTokenToVocabulary(string $token)
    {
        if ($this->isStopWord($token)) {
            return;
        }

        if (!isset($this->vocabulary[$token])) {
            $this->vocabulary[$token] = count($this->vocabulary);
        }
    }

    private function isStopWord(string $token): bool
    {
        return $this->stopWords && $this->stopWords->isStopWord($token);
    }

    private function updateFrequency(string $token)
    {
        if (!isset($this->frequencies[$token])) {
            $this->frequencies[$token] = 0;
        }

        ++$this->frequencies[$token];
    }

    private function checkDocumentFrequency(array &$samples)
    {
        if ($this->minDF > 0) {
            $beyondMinimum = $this->getBeyondMinimumIndexes(count($samples));
            foreach ($samples as &$sample) {
                $this->resetBeyondMinimum($sample, $beyondMinimum);
            }
        }
    }

    private function resetBeyondMinimum(array &$sample, array $beyondMinimum)
    {
        foreach ($beyondMinimum as $index) {
            $sample[$index] = 0;
        }
    }

    private function getBeyondMinimumIndexes(int $samplesCount) : array
    {
        $indexes = [];
        foreach ($this->frequencies as $token => $frequency) {
            if (($frequency / $samplesCount) < $this->minDF) {
                $indexes[] = $this->getTokenIndex($token);
            }
        }

        return $indexes;
    }
}
