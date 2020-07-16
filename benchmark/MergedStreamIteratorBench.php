<?php

/**
 * This file is part of prooph/event-store.
 * (c) 2014-2020 prooph software GmbH <contact@prooph.de>
 * (c) 2015-2020 Sascha-Oliver Prolic <saschaprolic@googlemail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace ProophBench\EventStore;

use PhpBench\Benchmark\Metadata\Annotations\Subject;
use Prooph\Common\Messaging\Message;
use Prooph\EventStore\StreamIterator\MergedStreamIterator;
use ProophTest\EventStore\StreamIterator\MergedStreamIteratorTest;

/**
 * @Revs(3)
 * @Iterations(3)
 * @Warmup(2)
 * @OutputTimeUnit("milliseconds", precision=5)
 * @BeforeMethods({"init"})
 * @Groups({"perf"})
 */
class MergedStreamIteratorBench
{
    private const NUMBER_OF_EVENTS = 977;

    private $eventStreams;
    private $eventStreamsShuffled;
    private $eventStreamsShuffledDate;
    private $eventStreamsShuffledStreams;

    public function init(): void
    {
        $this->eventStreams = MergedStreamIteratorTest::getStreamsLarge(self::NUMBER_OF_EVENTS, false, false);
        $this->eventStreamsShuffled = MergedStreamIteratorTest::getStreamsLarge(self::NUMBER_OF_EVENTS);
        $this->eventStreamsShuffledDate = MergedStreamIteratorTest::getStreamsLarge(self::NUMBER_OF_EVENTS, true, false);
        $this->eventStreamsShuffledStreams = MergedStreamIteratorTest::getStreamsLarge(self::NUMBER_OF_EVENTS, false, true);
    }

    /**
     * @Subject()
     */
    public function sorted(): void
    {
        $iterator = new MergedStreamIterator(
            \array_keys($this->eventStreamsShuffled),
            ...\array_values($this->eventStreamsShuffled)
        );

        /** @var Message $message $ */
        foreach ($iterator as $position => $message) {
            $message->uuid();
        }
    }

    /**
     * @Subject()
     */
    public function shuffled(): void
    {
        $iterator = new MergedStreamIterator(
            \array_keys($this->eventStreamsShuffled),
            ...\array_values($this->eventStreamsShuffled)
        );

        /** @var Message $message $ */
        foreach ($iterator as $position => $message) {
            $message->uuid();
        }
    }

    /**
     * @Subject()
     */
    public function shuffledDate(): void
    {
        $iterator = new MergedStreamIterator(
            \array_keys($this->eventStreamsShuffled),
            ...\array_values($this->eventStreamsShuffled)
        );

        /** @var Message $message $ */
        foreach ($iterator as $position => $message) {
            $message->uuid();
        }
    }

    /**
     * @Subject()
     */
    public function shuffledStreams(): void
    {
        $iterator = new MergedStreamIterator(
            \array_keys($this->eventStreamsShuffled),
            ...\array_values($this->eventStreamsShuffled)
        );

        /** @var Message $message $ */
        foreach ($iterator as $position => $message) {
            $message->uuid();
        }
    }

    /**
     * @Subject()
     */
    public function sortedUsort(): void
    {
        $iterator = new MergeStreamIteratorUsort(
            \array_keys($this->eventStreamsShuffled),
            ...\array_values($this->eventStreamsShuffled)
        );

        /** @var Message $message $ */
        foreach ($iterator as $position => $message) {
            $message->uuid();
        }
    }

    /**
     * @Subject()
     */
    public function shuffledUsort(): void
    {
        $iterator = new MergeStreamIteratorUsort(
            \array_keys($this->eventStreamsShuffled),
            ...\array_values($this->eventStreamsShuffled)
        );

        /** @var Message $message $ */
        foreach ($iterator as $position => $message) {
            $message->uuid();
        }
    }

    /**
     * @Subject()
     */
    public function shuffledDateUsort(): void
    {
        $iterator = new MergeStreamIteratorUsort(
            \array_keys($this->eventStreamsShuffled),
            ...\array_values($this->eventStreamsShuffled)
        );

        /** @var Message $message $ */
        foreach ($iterator as $position => $message) {
            $message->uuid();
        }
    }

    /**
     * @Subject()
     */
    public function shuffledStreamsUsort(): void
    {
        $iterator = new MergeStreamIteratorUsort(
            \array_keys($this->eventStreamsShuffled),
            ...\array_values($this->eventStreamsShuffled)
        );

        /** @var Message $message $ */
        foreach ($iterator as $position => $message) {
            $message->uuid();
        }
    }
}
