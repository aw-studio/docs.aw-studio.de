@if ($type == 'dark')
    <a 
        class="inline-flex items-center px-8 py-2 text-lg font-normal text-black bg-white border-2 border-black rounded-sm cursor-pointer hover:bg-black hover:text-white " 
        href="{{ $link }}" 
        target="{{ $target }}" 
        @if($target == '_blank') rel="noreferrer"@endif>
        {!! $slot !!}
    </a>
@endif
@if ($type == 'light')
    <a class="inline-flex items-center px-8 py-2 text-lg font-normal text-white bg-black border-2 border-white rounded-sm cursor-pointer hover:bg-white hover:text-black " 
        href="{{ $link }}" 
        target="{{ $target }}" 
        @if($target == '_blank')rel="noreferrer" @endif>
        {!! $slot !!}
    </a>
@endif