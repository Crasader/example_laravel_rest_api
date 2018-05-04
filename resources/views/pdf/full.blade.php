@extends('pdf.layout')

@section('title', 'Full')

@section('document-header', sprintf('%s - Full Information', $name))

@section('user-short-information', sprintf('%s (%s)', $name, $email))

@section('custom-part')
    {{ $text }}
@endsection
