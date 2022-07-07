<article id="{{ $report->id }}"
         class="border rounded-lg my-4 mx-4 p-5">
    <section class="flex flex-row justify-between">
        <div>
            <a href="{{ $report->permalink }}">&#35;{{ $report->id }}</a>
            @isset($report->date)
                &middot; <abbr title="{{ $report->date->format('m/d/Y') }}">{{ $report->date->diffForHumans() }}</abbr>
            @endisset
            &middot; &hearts; {{ $report->likes }}
        </div>
        <div class="hidden flex-none">
            <a href="#">Copy</a>
            <a href="#">Share</a>
        </div>
    </section>
    <section>
        @foreach($report->dialogues as $dialogue)
            <dl>
                <dt class="font-bold">{{ $dialogue->speaker }}</dt>
                <dd class="mb-2">{!! $dialogue->lineHtml !!}</dd>
            </dl>
        @endforeach
    </section>
    <section class="flex flew-row justify-between">
        <div>
            @isset($report->footnote)
                <b>Footnote:</b> {{ $report->footnote }}
            @endisset
        </div>
        <div>
            @foreach($report->tags as $tag)
                <a href="#" class="flex-auto rounded p-1.5 bg-gray-600 text-white">&#35;{{ $tag->name }}</a>
            @endforeach
        </div>
    </section>
</article>
