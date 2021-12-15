@extends('layouts.layout')
@section('content')

<div id="content" class="content">
    <div class="container mailing-view">
        <h1>Contenu du mail</h1>
        <a href="/mails">Aller à la liste</a>
        <div class="mailing-content">
            <table border="0" width="100%" style="font-size: 14px;">
                {{-- <tr>
                    <td align="center" bgcolor="#ffffff" style="padding: 20px 20px 20px 40px; border-bottom: 1px solid #989898;">
                        <img alt="logo" src="/assets/img/logo/logo_mail.png" />
                    </td>
                </tr> --}}
                <tr>
                    <td bgcolor="#f3f3f6" style="text-align: left; padding: 20px 20px 20px 40px; border-bottom: 1px solid #989898;">
                        aaaaaaa{{-- <p>{!! $mail->message !!}</p> --}}
                    </td>
                </tr>
                {{-- <tr>
                    <td bgcolor="#e1e4ec" style="padding: 20px 20px 20px 40px; border-bottom: 1px solid #989898;">
                        <span style="font-size: 28px;">INTERGATE LOGISTIC</span><br />
                        <span style="color: #6359ab; font-weight: bold;">Test Test / International Logistic Manager</span><br />
                        <br />
                        <table border="0" width="100%" style="text-align: left;">
                            <tr>
                                <td style="font-weight: bold; width: 150px;">Phone:</td>
                                <td>0033180855191</td>
                            </tr>
                            <tr>
                                <td style="font-weight: bold;">Mobile:</td>
                                <td>0033761910406</td>
                            </tr>
                            <tr>
                                <td style="font-weight: bold;">Web:</td>
                                <td>www.intergate-logistic.com</td>
                            </tr>
                            <tr>
                                <td style="font-weight: bold;">Mail:</td>
                                <td>transport2@intergate-logistic.com</td>
                            </tr>
                            <tr>
                                <td style="font-weight: bold;">Skype:</td>
                                <td>intergate.logistic6</td>
                            </tr>
                            <tr>
                                <td style="font-weight: bold;">VAT:</td>
                                <td>FR95527908883</td>
                            </tr>
                            <tr>
                                <td style="font-weight: bold;">DUNS:</td>
                                <td>262558982</td>
                            </tr>
                            <tr>
                                <td style="font-weight: bold;">CMR Insurance:</td>
                                <td>1.000.000 €</td>
                            </tr>
                            <tr>
                                <td style="font-weight: bold;">Capital:</td>
                                <td>258.000 €</td>
                            </tr>
                            <tr>
                                <td colspan="2">INTERGATE LOGISTIC - <span style="color: #6359ab; text-decoration: underline;">149, route de Melun, 91250 Saintry sur Seine, France</span> - 91008 EVRY CEDEX France</td>
                            </tr>
                        </table>
                    </td>
                </tr> --}}
                {{-- <tr>
                    <td bgcolor="#ffffff" style="text-align: left; padding: 20px 20px 20px 40px; font-size: 12px;">
                        <a style="text-decoration: none; padding: 6px 12px; font-weight: 600; color: #333; background: #fff; border: 1px solid #e2e7eb;" href="#">Unsuscribe</a>
                    </td>
                </tr> --}}
            </table>
            {{-- <div style="display: none; width: 1000px; background-color: #edeff2; padding: 20px;">
                <p style="text-align: center;">
                    <img alt="logo" src="/assets/img/logo/logo_mail.png" />
                </p>
                <br />
                <br />

                <hr />
                <br />
                <br />
                <p></p>
                <p>az</p>

                <hr />
                <br />
                <p style="text-align: center; font-size: 30px;"><strong>INTERGATE LOGISTIC</strong></p>
                <br />
                <hr />
                <p>
                    <span style="font-size: 20px;">International Logistic Manager</span><br />
                    <br />

                    <span>Phone:</span><span style="color: red;"> 0033180855191</span><br />
                    <span>Mobile:</span><span style="color: red;"> 0033761910406</span><br />
                    <span>Web:</span><span style="color: red;"> www.intergate-logistic.com</span><br />
                    <span>Mail:</span><span style="color: red;"> transport2@intergate-logistic.com</span><br />
                    <span>Skype:</span><span style="color: red;"> intergate.logistic6</span><br />
                    <br />

                    <span>VAT: FR95527908883</span><br />
                    <span>DUNS: 262558982</span><br />
                    <span>CMR Insurance: 1.000.000 €</span><br />
                    <span>Capital: 258.000 €</span><br />
                    <br />

                    <span>Adress :</span>
                    <span style="color: #ae4a34;">
                        INTERGATE
                        <a href="https://www.google.com/maps/place/42+Rue+Paul+Claudel,+91000+%C3%89vry/@48.6376196,2.4291048,17z/data=!3m1!4b1!4m5!3m4!1s0x47e5de23ed21fdd1:0x568db99293527174!8m2!3d48.6376196!4d2.4312935">
                            42 RUE PAUL CLAUDEL
                        </a>
                        91008 CEDEX -France-
                    </span>
                    <br />
                    <br />

                    <span>
                        For any reclamation or notice please write to reclamation@intergate-logistic.com This message is protected by the secrecy of correspondence rules; furthermore it may contain privileged or confidential information
                        that is protected by law, notably by the secrecy of business relations rule; it is intended solely for the attention of the addressee. Any disclosure, use, dissemination or reproduction (either whole or partial) of
                        this message or the information contained herein is strictly prohibited without prior consent. Any electronic message is susceptible to alteration and its integrity can not be assured. Intergate Logistic company
                        decline any responsibility for this message in the event of alteration or falsification. If you are not the intended recipient, please destroy it immediately and notify the sender of the wrong delivery and the mail
                        deletion.
                    </span>
                </p>
                <br />
            </div> --}}
        </div>
        {{-- <a class="btn btn-primary" href="{{ route('mails.edit',$mail->id) }}">Edit</a> --}}
        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#exampleModal"> Delete </button>

  
  <!-- Modal -->
  <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog ">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Delete Email</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            {{-- Are you sure you want to delete "{{$mail->object}}" --}}
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          {{-- <form action="{{ route('mails.destroy', ['mail' => $mail->id]) }}" method="POST"> --}}
            
            <input type="hidden" name="_method" value="DELETE">
            {{-- <button type="submit" class="btn btn-danger btn-block">Delete</button> --}}
            <button type="submit" class="btn btn-danger"> Delete </button>
        </form>
        </div>
      </div>
    </div>
  </div>
        {{-- <form action="{{ url('mails.destroy',$mail->id) }}" method="POST">   
             <a class="btn btn-primary" href="#">Edit</a>
            <button type="submit" class="btn btn-danger">Delete</button>
        </form> --}}

        {{-- <div class="text-center col">
            <div class="col-sm-2">
                <a class="btn btn-block btn-primary" href="/intra/mailing/52225/update">Modifier</a>
            </div>
            <div class="col-sm-2">
                <form name="form" method="post" action="/intra/mailing/52225/delete">
                    <input type="hidden" name="_method" value="DELETE" />
                    <input class="btn btn-block btn-danger" type="submit" value="Supprimer" />
                    <input type="hidden" id="form__token" name="form[_token]" value="ccax1kBz1WyFjB_CAat45NFzR2mQ6FAGQzBknkbMkHQ" />
                </form>
            </div>
        </div> --}}
    </div>

    
</div>
@endsection

