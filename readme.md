## Laravel Testcase

Make a good readable json object with this parameters;
<pre pattern> => <return pattern>

Serial, a pattern base validator.
Number, wildcard number #
Letter, wildcard letter %
Date, wildcard date ?

Example:
serial: WO########
date: 2010-??-??

{{convert into}}

Only interested in output serial
serial: WO####

Output of this:
WO######## => WO####
WO12345678 => WO5678

Other example pattern:
D##%%% => D#%%%
D12ABC => D2ABC

serial: WO########, date: 200?-??-?? => WO####
WO12345678, 2008 => WO5678 (valid date)
