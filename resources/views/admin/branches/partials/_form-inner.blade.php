<form method="post" enctype="multipart/form-data"
      @if(is_null($branch->id))
      action="{{route('admin.branches.store',['type'=> request()->type])}}"
      @else
      action="{{route('admin.branches.update',['type'=> request()->type,$branch->uuid])}}"
      @endif
      ref="main-form"
>
    {{csrf_field()}}
    @if(!is_null($branch->id))
        {{method_field('put')}}
    @endif
    <div class="row mb-4">
        <div class="col-md-12">
            {{--<div class="col-12">
                <ul class="nav nav-tabs border-bottom-0">
                    @foreach(localization()->getSupportedLocales() as $key => $locale)
                        <li class="nav-item">
                            <a class="nav-link {{ $key == localization()->getDefaultLocale() ? 'active' : '' }}"
                               data-toggle="tab"
                               href="#title_{{$key}}">
                                <span class="hidden-sm-up"><i class="ti-home"></i></span>
                                <span class="hidden-xs-down">{{$locale->native()}}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
                <div class="tab-content card card-outline-inverse">
                    @foreach(localization()->getSupportedLocales() as $langKey => $locale)
                        <div
                            class="tab-pane {{ $langKey == localization()->getDefaultLocale() ? 'active' : '' }}"
                            id="title_{{$langKey}}">
                            <div class="card-body pb-0">
                                <div class="row p-t-20">
                                    <div class="col-md-12">
                                        @component('admin.components.form-group', ['name' => $langKey .'[title]', 'type' => 'text'])
                                            @slot('label', trans('strings.name'))
                                            @if(! is_null($branch->id))
                                                @slot('value', optional($branch->translate($langKey))->title)
                                            @endif
                                        @endcomponent
                                    </div>
                                    <div class="col-md-12">
                                        <x-admin.textarea :id="$langKey.'-description'"
                                                          :name="$langKey.'[description]'"
                                                          label="Description"
                                                          :content="optional($branch->translate($langKey))->description"/>
                                    </div>

                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>--}}
            <div class="col-md-12">
                <div class="row">
                    @foreach(localization()->getSupportedLocales() as $langKey => $locale)
                        <div class="col-md-4 mt-4">
                            <div class="card card-outline-inverse">
                                <h4 class="card-header">{{Str::upper($langKey)}}</h4>
                                <div class="card-body row">
                                    <div class="col-md-12">
                                        @component('admin.components.form-group', ['name' => $langKey .'[title]', 'type' => 'text'])
                                            @slot('label', trans('strings.name'))
                                            @if(! is_null($branch->id))
                                                @slot('value', optional($branch->translate($langKey))->title)
                                            @endif
                                        @endcomponent
                                    </div>
                                    <div class="col-md-12">
                                        {{--<x-admin.textarea :id="$langKey.'-description'"--}}
                                        {{--                  :name="$langKey.'[description]'"--}}
                                        {{--                  label="Description"--}}
                                        {{--                  :content="optional($branch->translate($langKey))->description"/>--}}

                                        @component('admin.components.form-group', ['name' => $langKey .'[description]', 'type' => 'textarea'])
                                            @slot('label', 'Description')
                                            @slot('attributes', [
                                                'rows' => 5,
                                            ])
                                            @if(! is_null($branch->id))
                                                @slot('value', optional($branch->translate($langKey))->description)
                                            @endif
                                        @endcomponent
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="col-md-12 mt-2">
                <div class="card card-outline-inverse">
                    <h4 class="card-header">Address</h4>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label class="control-label">
                                                @lang('strings.city')
                                                <b class="text-danger">*</b>
                                            </label>
                                            <multiselect
                                                :options="regions"
                                                v-model="branch.region"
                                                track-by="name"
                                                label="name"
                                                :searchable="true"
                                                :allow-empty="true"
                                                select-label=""
                                                selected-label=""
                                                deselect-label=""
                                                placeholder=""
                                                @select="retrieveCities"
                                                autocomplete="false"
                                            ></multiselect>
                                            @error('city')
                                            <small class="form-text text-danger">
                                                {{$message}}
                                            </small>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label class="control-label">
                                                Neighborhood
                                                <b class="text-danger">*</b>
                                            </label>
                                            <multiselect
                                                :options="cities"
                                                v-model="branch.city"
                                                track-by="name"
                                                label="name"
                                                :searchable="true"
                                                :allow-empty="true"
                                                select-label=""
                                                selected-label=""
                                                deselect-label=""
                                                placeholder=""
                                                {{--                                        @select="retrieveNeighborhoods"--}}
                                                autocomplete="false"
                                            ></multiselect>
                                            @error('region')
                                            <small class="form-text text-danger">
                                                {{str_replace('region', 'neighborhood', $message)}}
                                            </small>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        @component('admin.components.form-group', ['name' => 'latitude', 'type' => 'text'])
                                            @slot('label', 'Latitude')
                                            @slot('attributes', [
                                               'id' => 'latitude'
                                           ])
                                            @slot('value', $branch->latitude)
                                        @endcomponent
                                    </div>
                                    <div class="col-12">
                                        @component('admin.components.form-group', ['name' => 'longitude', 'type' => 'text'])
                                            @slot('label', 'Longitude')
                                            @slot('attributes', [
                                               'id' => 'longitude'
                                           ])
                                            @slot('value', $branch->longitude)
                                        @endcomponent
                                    </div>
                                    <div class="col-12">
                                        <a href="https://maps.google.com/?q={{$branch->latitude}},{{$branch->longitude}}"
                                           target="_blank">
                                            Open In Google Maps <i class="fas fa-external-link-alt"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6" style="height: 250px;">
                                <div id="gmaps-branch" style="height: 100%; width: 100%;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12 mt-2">
                <div class="card card-outline-inverse">
                    <h4 class="card-header">Details</h4>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">
                                        Chain
                                    </label>
                                    <multiselect
                                        :options="chains"
                                        v-model="branch.chain"
                                        track-by="title"
                                        label="title"
                                        :searchable="true"
                                        :allow-empty="true"
                                        select-label=""
                                        selected-label=""
                                        deselect-label=""
                                        placeholder=""
                                        autocomplete="false"
                                    ></multiselect>
                                </div>
                            </div>
                            @if($type == \App\Models\Branch::CHANNEL_FOOD_OBJECT)
                                <div class="col-6">
                                    @component('admin.components.form-group', ['name' => 'food_categories[]', 'type' => 'select'])
                                        @slot('label', 'Food categories')
                                        @slot('attributes', [
                                           'class' => 'select2-categories w-100',
                                           'multiple'
                                       ])
                                        @slot('options', $foodCategories->pluck('title','id')->prepend('',''))
                                        @slot('selected', $branch->foodCategories)
                                    @endcomponent
                                </div>
                                <div class="col-6">
                                    @component('admin.components.form-group', ['name' => 'featured_at', 'type' => 'datetime-local'])
                                        @slot('label', 'Featured at')
                                        @slot('value', $branch->featured_at)
                                    @endcomponent
                                </div>
                                <div class="col-6">
                                    @component('admin.components.form-group', ['name' => 'search_tags[]', 'type' => 'select'])
                                        @slot('label', 'Search Tags')
                                        @slot('attributes', [
                                           'class' => 'select2-search-tags w-100',
                                           'multiple'
                                       ])
                                        @slot('options', $searchTags->pluck('title','id')->prepend('',''))
                                        @slot('selected', $branch->searchTags)
                                    @endcomponent
                                </div>
                            @endif
                            <div class="col-6">
                                @component('admin.components.form-group', ['name' => 'status', 'type' => 'select'])
                                    @slot('label', trans('strings.status'))
                                    @slot('options', \App\Models\Branch::getStatusesArray())
                                    @slot('selected', $branch->status)
                                @endcomponent
                            </div>

                            <div class="col-md-6 {{$type == \App\Models\Branch::CHANNEL_FOOD_OBJECT ? '':'mt-3'}}">
                                @component('admin.components.form-group', ['name' => 'primary_phone_number', 'type' => 'tel'])
                                    @slot('label', 'Primary phone number')
                                    @slot('value', $branch->primary_phone_number)
                                @endcomponent
                            </div>
                            {{--<div class="col-md-4 mt-3">
                                @component('admin.components.form-group', ['name' => 'secondary_phone_number', 'type' => 'tel'])
                                    @slot('label', 'Secondary phone number')
                                    @slot('value', $branch->secondary_phone_number)
                                @endcomponent
                            </div>
                            <div class="col-md-4 mt-3">
                                @component('admin.components.form-group', ['name' => 'whatsapp_phone_number', 'type' => 'tel'])
                                    @slot('label', 'Whatsapp phone number')
                                    @slot('value', $branch->whatsapp_phone_number)
                                @endcomponent
                            </div>--}}
                        </div>
                    </div>
                </div>
            </div>

            @include('admin.branches.partials._branch-delivery-methods')
            @include('admin.branches.partials._branch-contacts')
        </div>
    </div>
    <div class="col-md-12">
        <input type="hidden" name="region" :value="JSON.stringify(branch.region)">
        <input type="hidden" name="city" :value="JSON.stringify(branch.city)">
        <input type="hidden" name="chain" :value="JSON.stringify(branch.chain)">
        <input type="hidden" name="contactDetails" :value="JSON.stringify(contactDetails)">
        {{--        <input type="hidden" name="longitude" id="longitude">--}}
        {{--        <input type="hidden" name="latitude" id="latitude">--}}
        <input type="hidden" name="unattached-media" class="deleted-file" value="">
        <div class="col-md-12" v-if="formErrorMessage">
            <p class="text-danger text-capitalize">@{{ formErrorMessage }}</p>
        </div>
        <button class="btn btn-success" type="submit"
                @click="submitButton($event)">{{trans('strings.submit')}}</button>
    </div>
</form>
