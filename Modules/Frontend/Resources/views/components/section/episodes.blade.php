
<div id="season-card-wrapper" class="section-spacing-bottom px-0">
    <div class="container-fluid">
        <div class="seasons-tabs-wrapper position-relative">
            <div class="season-tabs-inner">
                <div class="left">
                    <i class="ph ph-caret-left"></i>
                </div>
                <div class="season-tab-container custom-nav-slider">
                    <ul class="nav nav-tabs season-tab" id="season-tab" role="tablist">
                        @foreach ($data as $index => $item)
                        <li class="nav-item" role="presentation">
                            <style>
                                /* শুরুতে সবার জন্য নিয়ম */
                                .tv-only {
                                    display: none;
                                }
                                .not-tv {
                                    display: block;
                                }
                                
                                /* এখন TV রেঞ্জের জন্য (৭৭৬px - ১১৯৯px) */
                                @media (min-width: 776px) and (max-width: 1199px) {
                                    .tv-only {
                                        display: block; /* TV হলে H4 দেখাবে */
                                    }
                                    .not-tv {
                                        display: none; /* TV হলে Button লুকাবে */
                                    }
                                }
                                </style>
                                
                                
                            {{-- Only show for TV --}}
                            <h4 class="tv-only">Season {{ $index + 1 }}</h4>

                            {{-- Show all device but hide for TV --}}
                            <button class="nav-link not-tv {{ $index == 0 ? 'active' : '' }}"
                                    id="season-{{ $index + 1 }}"
                                    data-bs-toggle="tab"
                                    data-bs-target="#season-{{ $index + 1 }}-pane"
                                    type="button"
                                    role="tab"
                                    aria-controls="season-{{ $index + 1 }}-pane"
                                    aria-selected="{{ $index == 0 ? 'true' : 'false' }}" 
                                    tabindex="{{ $index + 1 }}">
                                Season {{ $index + 1 }}
                            </button>

                            {{-- Old Main btn --}}
                            {{-- <button class="nav-link active" id="season-{{ (int)$index+1}}" data-bs-toggle="tab" data-bs-target="#season-{{ (int)$index+1 }}-pane" type="button" role="tab" aria-controls="season-{{ (int)$index+1 }}-pane" aria-selected="true">Season {{ (int)$index+1 }}</button> --}}
                        </li>
                        @endforeach
                    </ul>
                </div>
                <div class="right">
                    <i class="ph ph-caret-right"></i>
                </div>
            </div>
            <div class="tab-content" id="season-tab-content">


                @foreach($data as $index => $value)

                <div class="tab-pane fade show active" id="season-{{ (int)$index + 1 }}-pane" role="tabpanel" aria-labelledby="season-{{ (int)$index + 1 }}" tabindex="0">
                    <ul class="list-inline m-0 p-0 d-flex flex-column gap-4">

                        @foreach($item['episodes']->toArray(request()) as $index =>$episode)
                            <li>
                                @include('frontend::components.card.card_episode', ['data' => $episode, 'index'=>$index])
                            </li>
                        @endforeach
                    </ul>
                </div>
              @if(((int)count($value['episodes'])>3))
                <div class="viewmore-button-wrapper">
                  <button class="btn btn-dark" >{{__('frontend.view_more')}}</button>
                </div>
            @endif
            @endforeach
              </div>
        </div>
    </div>
</div>
