@extends('pdf.layout')

@section('title', 'Advanced')

@section('document-header', sprintf('%s - Advanced Information', $name))

@section('user-short-information', sprintf('%s (%s)', $name, $email))

@section('custom-part')
    {{ $text }}
@endsection
