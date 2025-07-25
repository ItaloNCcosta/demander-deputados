<?php

declare(strict_types=1);

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

final class Pagination extends Component
{
    public LengthAwarePaginator $paginator;
    public int $maxLinks;
    public int $startPage;
    public int $endPage;

    public function __construct(LengthAwarePaginator $paginator, int $maxLinks = 5)
    {
        $this->paginator = $paginator;
        $this->maxLinks = $maxLinks;
        $this->calculateRange();
    }

    protected function calculateRange(): void
    {
        $current = $this->paginator->currentPage();
        $last    = $this->paginator->lastPage();
        $half    = (int) floor($this->maxLinks / 2);

        $start = max(1, $current - $half);
        $end   = min($last, $current + $half);

        if ($current <= $half) {
            $end = min($last, $this->maxLinks);
        }

        if ($current + $half > $last) {
            $start = max(1, $last - $this->maxLinks + 1);
        }

        $this->startPage = $start;
        $this->endPage   = $end;
    }

    public function render(): View|Closure|string
    {
        return view('components.pagination', [
            'startPage' => $this->startPage,
            'endPage'   => $this->endPage,
        ]);
    }
}
