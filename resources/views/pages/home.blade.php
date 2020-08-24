@extends('app')

@section('bodyclass')
aw-first-section-is-black
@endsection

@section('appclass')
aw-home
@endsection

@section('meta')
<title>Open Source - Alle Wetter</title>
<x-fj-meta-tags 
    :title="$page->metaTitle" 
    :description="$page->metaDescription" 
    :keywords="$page->metaKeywords"
/>
@endsection

@section('content')

<section class="bg-black">
    <div class="container pt-20">
        <div class="grid grid-cols-12 gap-5 mb-20">
            <div class="col-start-1 col-span-12 lg:col-span-9">
                <h1 class="text-xl mb-4 text-white">
                    Open Source
                </h1>
                <h2 class="h1">
                    Packages and Projects
                </h2>
            </div>
        </div>
    </div>
</section>

<section class="bg-white py-20">

    <div class="container">
        <h2 class="h2">
            A-Z
        </h2>
        <div class="pb-40">

            <hr class="text-black opacity-25">
        
            @foreach($repos as $repo => $config)
                @if(!Gate::has("docdress.{$repo}") || Gate::check("docdress.{$repo}"))
                    <div class="grid grid-cols-12 gap-5 row-gap-0 py-4">
                        <div class="
                            col-start-1 col-span-12
                            lg:col-start-1 lg:col-span-4
                            font-semibold text-base py-0
                        ">
                            <a class="aw-link" href="{{ $config['route_prefix'] }}">
                                <b>{{ $config['title'] ?? '' }}</b>
                            </a>
                        </div>
                        <div class="
                            col-start-1 col-span-12
                            lg:col-start-5 lg:col-span-4
                            text-base py-0
                        ">
                            {{ $config['description'] ?? '' }}
                        </div>
                        <div class="
                            col-start-1 col-span-6 inline-flex
                            lg:col-start-9 lg:col-span-2
                            text-base py-0
                        ">
                            <span class="inline-flex mr-2">{{ $config['downloads'] ?? 0 }} @include('icons.download')</span>
                            <span class="inline-flex">{{ $config['stars'] ?? 0 }} @include('icons.star')</span>
                        </div>
                        <div class="
                            hidden col-start-7 col-span-6 text-base text-right
                            sm:block
                            lg:col-start-11 lg:col-span-2
                        ">
                            <a class="aw-link inline-flex" href="https://github.com/{{ $repo }}" target="_blank" rel="noreferrer">
                                <span class="mr-2">Repository</span> @include('icons.github')
                            </a>
                        </div>
                    </div>
                
                    <hr class="text-black opacity-25">
                @endif
            @endforeach
        </div>
    </div>

</section>

@endsection