@extends('layouts.default')

@section('page_title')
    Règles de sécurité
@endsection

{{-- @php $content_padding = 'custom-padding'; @endphp --}}
@section('content')
    <h2 class="text-3xl leading-tight font-extrabold text-gray-900">
      Des gestes simples pour préserver votre santé et celle des autres
    </h2>
    <div class="mt-6 border-t-2 border-gray-100 pt-10">
      <dl class="md:grid md:grid-cols-2 md:gap-8">
        <div>
          <div>
            <dt class="text-lg leading-tight font-bold text-gray-900">
              Je me lave très régulièrement les mains
            </dt>
            <dd class="mt-2">
              <p class="text-base leading-6 text-gray-500">
                Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
              </p>
            </dd>
          </div>
          <div class="mt-12">
            <dt class="text-lg leading-tight font-bold text-gray-900">
              Je tousse ou éternue dans mon coude ou dans un mouchoir
            </dt>
            <dd class="mt-2">
              <p class="text-base leading-6 text-gray-500">
                Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
              </p>
            </dd>
          </div>
          <div class="mt-12">
            <dt class="text-lg leading-tight font-bold text-gray-900">
              J’utilise des mouchoirs à usage unique et je les jette
            </dt>
            <dd class="mt-2">
              <p class="text-base leading-6 text-gray-500">
                Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
              </p>
            </dd>
          </div>
        </div>
        <div class="mt-12 md:mt-0">
          <div>
            <dt class="text-lg leading-tight font-bold text-gray-900">
              Je salue sans serrer la main, j’arrête les embrassades
            </dt>
            <dd class="mt-2">
              <p class="text-base leading-6 text-gray-500">
                Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
              </p>
            </dd>
          </div>
          <div class="mt-12">
            <dt class="text-lg leading-tight font-bold text-gray-900">
              Je reste chez moi
            </dt>
            <dd class="mt-2">
              <p class="text-base leading-6 text-gray-500">
                Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
              </p>
            </dd>
          </div>
        </div>
      </dl>
    </div>
@endsection
