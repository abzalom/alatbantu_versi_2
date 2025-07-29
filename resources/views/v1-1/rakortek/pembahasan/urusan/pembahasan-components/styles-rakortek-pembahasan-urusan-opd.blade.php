@if (auth()->user()->hasRole('admin'))
    <style>
        .info-card {
            /* Jika role admin */
            border-left: 5px solid #007bff;

        }

        .info-card-body {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 10px;
            text-align: center
        }

        .info-card-title {
            font-size: 12px;
            font-weight: bold;
            color: #007bff;
        }

        .info-card-text {
            flex-grow: 1;
        }

        .info-card-data {
            font-size: 1.8rem;
            color: #6c757d;
            text-align: center;
        }

        .info-card-icon {
            font-size: 2rem;
            color: #007bff;
            border-right: 1px solid #d0d0d0;
            width: 30%;
            text-align: center;
            margin: 0;
            padding-right: 10px;
        }
    </style>
@else
    <style>
        .info-card {
            /* Jika role user */
            background-color: #007bff;
            color: #fff;
            border-left: #bfff00 5px solid;
        }

        .info-card-body {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 10px;
            text-align: center
        }

        .info-card-title {
            font-size: 12px;
            font-weight: bold;
            color: #fff;
        }

        .info-card-text {
            flex-grow: 1;
        }

        .info-card-data {
            font-size: 1.8rem;
            text-align: center;
            color: #fff;
        }

        .info-card-icon {
            font-size: 2rem;
            color: #fff;
            border-right: 1px solid #d0d0d0;
            width: 30%;
            text-align: center;
            margin: 0;
            padding-right: 10px;
        }
    </style>
@endif
