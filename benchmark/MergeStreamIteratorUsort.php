<?php
/**
 * This file is part of the prooph/arangodb-event-store.
 * (c) 2017-2018 prooph software GmbH <contact@prooph.de>
 * (c) 2017-2018 Sascha-Oliver Prolic <saschaprolic@googlemail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace ProophBench\EventStore;

use Prooph\Common\Messaging\Message;
use Prooph\EventStore\StreamIterator\StreamIterator;

final class MergeStreamIteratorUsort implements StreamIterator
{
    /**
     * @var array
     */
    private $iterators = [];

    /**
     * @var int
     */
    private $numberOfIterators;

    public function __construct(array $streamNames, StreamIterator ...$iterators)
    {
        foreach ($iterators as $key => $iterator) {
            $this->iterators[$key][0] = $iterator;
            $this->iterators[$key][1] = $streamNames[$key];
        }
        $this->numberOfIterators = \count($this->iterators);

        $this->prioritizeIterators();
    }

    public function rewind(): void
    {
        foreach ($this->iterators as $iter) {
            $iter[0]->rewind();
        }

        $this->prioritizeIterators();
    }

    public function valid(): bool
    {
        foreach ($this->iterators as $key => $iterator) {
            if ($iterator[0]->valid()) {
                return true;
            }
        }

        return false;
    }

    public function next(): void
    {
        // only advance the prioritized iterator
        $this->iterators[0][0]->next();

        $this->prioritizeIterators();
    }

    public function current()
    {
        return $this->iterators[0][0]->current();
    }

    public function streamName(): string
    {
        return $this->iterators[0][1];
    }

    public function key(): int
    {
        return $this->iterators[0][0]->key();
    }

    public function count(): int
    {
        $count = 0;
        foreach ($this->iterators as $iterator) {
            $count += \count($iterator[0]);
        }

        return $count;
    }

    private function prioritizeIterators(): void
    {
        $compareValue = function (\Iterator $iterator): \DateTimeImmutable {
            /** @var Message $message */
            $message = $iterator->current();

            return $message->createdAt();
        };

        $compareFunction = function ($a, $b) use ($compareValue) {
            // valid iterators should be prioritized over invalid ones
            if (! $a[0]->valid() || ! $b[0]->valid()) {
                return $b[0]->valid() <=> $a[0]->valid();
            }

            return $compareValue($a[0]) <=> $compareValue($b[0]);
        };

        if ($this->numberOfIterators > 1) {
            \usort($this->iterators, $compareFunction);
        }
    }
}
