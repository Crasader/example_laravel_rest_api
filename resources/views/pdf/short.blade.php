@extends('pdf.layout')

@section('title', 'Short')

@section('document-header', sprintf('%s - Short Information', $name))

@section('user-short-information', sprintf('%s (%s)', $name, $email))

@section('custom-part')
    {{ $text }}
@endsection
