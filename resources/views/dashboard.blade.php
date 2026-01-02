<x-app-layout>
    

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                
            <div class="p-6 text-gray-900">
            <div class="p" style="line-height: 1.6em; margin: 10px 0px; text-align: justify; color: rgb(34, 34, 34); font-family: Arial, Helvetica, sans-serif;">
            <strong>Biedrība&nbsp; AFA &quot;OLAINE&quot;</strong><br />
Juridiskā adrese: Parka iela 11-16, Olaine, Olaines nov., LV-2114,<br />
Reg.Nr. 40008218534<br />
AS &quot;Swedbank&quot;, Konta Nr. LV58HABA0551038009878, Kods : HABALV22<br />
<br />
<span style="font-size:14px;"><strong>AFA OLAINE kluba logo</strong></br><br />
<a href="https://www.afaolaine.lv/files/userfiles/files/AFA%20OLAINE%20logo%202022%20final%20no%20name-5.png"><img alt="" src="https://www.afaolaine.lv/files/userfiles/images/AFA%20OLAINE%20logo%202022%20final%20no%20name-5.png" style="width: 80px; height: 120px;" /></a></span><br />
<span style="font-size:14px;"><strong>FK OLAINE kluba logo</strong><br /><br />
<a href="https://www.afaolaine.lv/files/userfiles/files/FK%20OLAINE%20logo%202022%20final%20no%20background.png"><img alt="" src="https://www.afaolaine.lv/files/userfiles/images/FK%20OLAINE%20logo%202022%20final%20no%20background.png" style="width: 90px; height: 90px;" /></a></span><br />
<br />
<strong>Biedrība&nbsp; &quot;Futbola klubs&quot;Olaine&quot;&quot;</strong><br />
Juridiskā adrese: Parka 11 - 16 , Olaine, Olaines novads, LV-2114,<br />
Reg.Nr. 50008130491<br />
AS&quot;Swedbank&quot; Konta Nr. LV44HABA0551028093917 Kods : HABALV22<br />
<br />
<strong>Biedrība&nbsp; &quot;Futbola klubs&quot;Olaine&quot;&quot; (sievie&scaron;u nodaļa)</strong><br />
Juridiskā adrese: Parka 11 - 16 , Olaine, Olaines novads, LV-2114,<br />
Reg.Nr. 50008130491,<br />
AS&quot;Swedbank&quot; Konta Nr. LV52HABA0551045749228 Kods : HABALV22</div>
</div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
