<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>Integration Shopify App</title>

  <!-- Styles -->
  <link rel="icon" href={{ asset('img/logo/favicon-new.svg') }} type="image/x-icon"/>
  <link
    rel="stylesheet"
    href="{{ asset('css/app.css') }}"
  />

  <style>
    .passport-authorize {
      width: 100%;
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .passport-authorize .container {
      display: flex;
      justify-content: center;
      width: 100%;
    }

    .container .item {
      width: 50%;
    }

    .form-auth {
      display: flex;
      justify-content: center;
      align-items: center;
      flex-direction: column;
      font-size: 18px;
      padding: 10px 0;
    }

    .container .card-header {
      font-weight: 700;
      font-size: 44px;
      line-height: 38px;
      margin-top: 32px;
    }

    .card-body .app-name {
      margin-top: 20px;
      font-weight: 500;
      font-size: 20px;
    }

    .passport-authorize .scopes {
      margin-top: 20px;
    }

    .passport-authorize form {
      display: inline;
    }

    .buttons {
      font-size: 18px;
      display: flex;
      justify-content: space-between;
      margin-top: 25px;
      text-align: center;
    }

    .title-integration {
      display: flex;
    }

    .lai-info {
      display: flex;
      align-items: center;
    }

    .info-header {
      font-weight: 700;
      font-size: 40px;
      line-height: 38px;
      margin-left: 10px;

    }


  </style>
</head>

<body class="passport-authorize">
<div class="container">

  <div class="item form-auth">
    <div class="title-integration">
    <span>
    </span>
    </div>
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card card-default">
          <div class="card-header">
            Authorization Form
          </div>
          <div class="card-body">
            <!-- Introduction -->
            <div class="app-name">
              <a target="_blank" class="Polaris-Link" href="{{$client->app_url}}" rel="noopener noreferrer"
                 style="display: flex;text-decoration: none"
                 data-polaris-unstyled="true">
                <div>{{ $client->name }}</div>
                <div>
                <span class="Polaris-Icon ">
                    <svg width=" 20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"
                         class="Polaris-Icon__Svg" focusable="false" aria-hidden="true">
                    <path fill-rule="evenodd" clip-rule="evenodd"
                          d="M14 13V14C14 14.553 13.553 15 13 15H6C5.425 15 5 14.516 5 14V7C5 6.448 5.448 6 6 6H7C8.03693 6 8.04053 7.5 7 7.5C6.82217 7.50502 6.64704 7.5 6.5 7.5V13.5H12.5C12.5 13.5 12.5 13.1778 12.5 13C12.5 12 14 12 14 13ZM10.25 5.75C10.25 5.33579 10.5858 5 11 5H15V9C15 9.41421 14.6642 9.75 14.25 9.75C13.8358 9.75 13.5 9.41421 13.5 9V7.56066L10.2803 10.7803C9.98744 11.0732 9.51256 11.0732 9.21967 10.7803C8.92678 10.4874 8.92678 10.0126 9.21967 9.71967L12.4393 6.5H11C10.5858 6.5 10.25 6.16421 10.25 5.75Z"
                          fill="var(--p-interactive)"/>
                  </svg>
                </span>

                </div>
              </a>
            </div>

            <!-- Scope List -->
            @if (count($scopes) > 0)
              <div class="scopes">
                <p>Requests for the integration:</p>

                <div style="display: block; margin: 10px 0;">
                  @foreach ($scopes as $scope)
                    <div style="display: flex; margin: 4px 0;">
                    <span
                      class="Polaris-Icon--colorSuccess"
                      style="display: inline-block;height: 1.25rem;width: 1.25rem;max-height: 100%;max-width: 100%;margin-right: 6px">
                    <span class="Polaris-Text--root Polaris-Text--visuallyHidden">
                    </span>
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                              d="M2 10C2 12.1217 2.84285 14.1566 4.34315 15.6569C5.84344 17.1571 7.87827 18 10 18C12.1217 18 14.1566 17.1571 15.6569 15.6569C17.1571 14.1566 18 12.1217 18 10C18 7.87827 17.1571 5.84344 15.6569 4.34315C14.1566 2.84285 12.1217 2 10 2C7.87827 2 5.84344 2.84285 4.34315 4.34315C2.84285 5.84344 2 7.87827 2 10ZM14.16 8.56C14.2755 8.40594 14.3316 8.21538 14.318 8.02329C14.3043 7.8312 14.2219 7.65048 14.0857 7.51431C13.9495 7.37815 13.7688 7.29566 13.5767 7.28201C13.3846 7.26836 13.1941 7.32446 13.04 7.44L9.2 11.28L7.36 9.44C7.20594 9.32446 7.01538 9.26836 6.82329 9.28201C6.6312 9.29566 6.45048 9.37815 6.31431 9.51431C6.17815 9.65048 6.09566 9.8312 6.08201 10.0233C6.06836 10.2154 6.12446 10.4059 6.24 10.56L8.64 12.96C8.96 13.28 9.44 13.28 9.76 12.96L14.16 8.56Z"
                              fill="#008060"/>
                    </svg>
                  </span>
                      <span style="font-weight: 600">{{ $scope->description }}</span>
                    </div>
                  @endforeach
                </div>
              </div>
            @endif
            <div style="margin-top: 20px">Do you agree to authorize us the mentioned fields?</div>
            <div class="buttons">
              <div></div>
              <div>
                <!-- Cancel Button -->
                <form method="post" action="{{ route('passport.authorizations.deny') . "?shop={$shop}" }}">
                  @csrf
                  @method('DELETE')

                  <input type="hidden" name="state" value="{{ $request->state }}">
                  <input type="hidden" name="client_id" value="{{ $client->id }}">
                  <input type="hidden" name="auth_token" value="{{ $authToken }}">
                  <button class="Polaris-Button" type="submit">
                  <span class="Polaris-Button__Content">
                    <span class="Polaris-Button__Text">Decline</span>
                  </span>
                  </button>
                </form>

                <!-- Authorize Button -->
                <form method="post"
                      action="{{ route('passport.authorizations.approve') . "?shop={$shop}" }}">
                  @csrf

                  <input type="hidden" name="state" value="{{ $request->state }}">
                  <input type="hidden" name="client_id" value="{{ $client->id }}">
                  <input type="hidden" name="auth_token" value="{{ $authToken }}">
                  <button class="Polaris-Button Polaris-Button--primary" type="submit">
                  <span class="Polaris-Button__Content">
                    <span class="Polaris-Button__Text">Authorize</span>
                  </span>
                  </button>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
</body>
</html>
