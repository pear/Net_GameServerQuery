<?php
// This file contains example packets for each protocol
// It can be useful for testing the protocol parsers

// HalfLife
$packets['HalfLife']['rules'][]     = base64_decode('/v///woPAAAC/////0VeAF90dXRvcl9ib21iX3ZpZXdhYmxlX2NoZWNrX2ludGVydmFsADAuNQBfdHV0b3JfZGVidWdfbGV2ZWwAMABfdHV0b3JfZXhhbWluZV90aW1lADAuNQBfdHV0b3JfaGludF9pbnRlcnZhbF90aW1lADEwLjAAX3R1dG9yX2xvb2tfYW5nbGUAMTAAX3R1dG9yX2xvb2tfZGlzdGFuY2UAMjAwAF90dXRvcl9tZXNzYWdlX2NoYXJhY3Rlcl9kaXNwbGF5X3RpbWVfY29lZmZpY2llbnQAMC4wNwBfdHV0b3JfbWVzc2FnZV9taW5pbXVtX2Rpc3BsYXlfdGltZQAxAF90dXRvcl9tZXNzYWdlX3JlcGVhdHMANQBfdHV0b3Jfdmlld19kaXN0YW5jZQAxMDAwAGFkbWluX2hpZ2hsYW5kZXIAMABhZG1pbl9pZ25vcmVfaW1tdW5pdHkAMABhZG1pbl9tb2RfdmVyc2lvbgAyLjUwLjU5IChNTSkAYWRtaW5fcXVpZXQAMABhbGxvd19jbGllbnRfZXhlYwAxAGFsbG93X3NwZWN0YXRvcnMAMS4wAGFtaV9zdl9tYXhwbGF5ZXJzADE2AGFtdl9wcml2YXRlX3NlcnZlcgAwAGNvb3AAMABkZWF0aG1hdGNoADEAZGVjYWxmcmVxdWVuY3kAMzAAZGVmYXVsdF9hY2Nlc3MAMABlZGdlZnJpY3Rpb24AMgBob3N0YWdlX2RlYnVnADAAaG9zdGFnZV9zdG9wADAAaHVtYW5zX2pvaW5fdGVhbQBhbnkAbWF4X3F1ZXJpZXNfc2VjADEAbWF4X3F1ZXJpZXNfc2VjX2dsb2JhbAAxAG1heF9xdWVyaWVzX3dpbmRvdwAxAG1ldGFtb2RfdmVyc2lvbgAxLjE3AG1wX2FsbG93bW9uc3RlcnMAMABtcF9hdXRva2ljawAwAG1wX2F1dG90ZWFtYmFsYW5jZQAxAG1wX2J1eXRpbWUAMS41AG1wX2M0dGltZXIANDUAbXBfY2hhdHRpbWUAMTAAbXBfY29uc2lzdGVuY3kAMQBtcF9mYWRldG9ibGFjawAwAG1wX2ZsYXNobGlnaHQAMABtcF9mb290c3RlcHMAMQBtcF9mb3JjZWNhbWVyYQAwAG1wX2ZvcmNlY2hhc2VjYW0AMABtcF9mcmFnc2xlZnQAMABtcF9mcmVlemV0aW1lADcAbXBfZnJpZW5kbHlmaXJlADAAbXBfZ2hvc3RmcmVxdWVuY3kAMC40AG1wX2hvc3RhZ2VwZW5hbHR5ADMAbXBfa2lja3BlcmNlbnQAMC42NgBtcF9saW1pdHRlYW1zADEAbXBfbG9nZGV0YWlsADAAbXBfbG9nZmlsZQAxAG1wX2xvZ21lc3NhZ2VzADEAbXBfbWFwdm90ZXJhdGlvADAuNjYAbXBfbWF4cm91bmRzADAAbXBfbWlycm9yZGFtYWdlADAAbXBfcGxheWVyaWQAMABtcF9yb3VuZHRpbWUANQBtcF9zdGFydG1vbmV5ADgwMABtcF90aW1lbGVmdAAwAG1wX3RpbWVsaW1pdAAzMABtcF90a3B1bmlzaAAwAG1wX3dpbmRpZmZlcmVuY2UAMQBtcF93aW5saW1pdAAwAHBhdXNhYmxlADAAcHVibGljX3Nsb3RzX2ZyZWUAMTYAcmVzZXJ2ZV9zbG90cwAwAHJlc2VydmVfdHlwZQAwAHN2X2FjY2VsZXJhdGUANQBzdl9haW0AMABzdl9haXJhY2NlbGVyYXRlADMAc3ZfYWlybW92ZQAxAHN2X2FsbG93dXBsb2FkADEAc3ZfYWxsdGFsawAwAHM=');
$packets['HalfLife']['rules'][]     = base64_decode('/v///woPAAASdl9ib3VuY2UAMQBzdl9jaGVhdHMAMABzdl9jbGllbnR0cmFjZQAxAHN2X2NsaXBtb2RlADAAc3ZfY29udGFjdAAAc3ZfZnJpY3Rpb24ANABzdl9ncmF2aXR5ADgwMABzdl9sb2dibG9ja3MAMABzdl9tYXhyYXRlADEwMDAwAHN2X21heHNwZWVkADkwMABzdl9taW5yYXRlADIwMDAAc3ZfcGFzc3dvcmQAMABzdl9wcm94aWVzADEAc3ZfcmVnaW9uADUAc3ZfcmVzdGFydAAwAHN2X3Jlc3RhcnRyb3VuZAAwAHN2X3N0ZXBzaXplADE4AHN2X3N0b3BzcGVlZAA3NQBzdl92b2ljZWVuYWJsZQAxAHN2X3dhdGVyYWNjZWxlcmF0ZQAxMABzdl93YXRlcmZyaWN0aW9uADEA');
$packets['HalfLife']['players']     = base64_decode('/////0QYAXBvbyBidW0AAAAAAP5ajEUCeEYgfCBEYXp6bGUAAAAAAC04z0UDQ3JvdwABAAAANAJ7RARbTW9EXS1UaGFfVmFtcHlyAAAAAACrnMpFBUt5bmUAAAAAALcNckUGQWdlbnQgRmxhbXplAAEAAADhjyJFB1tFdmFdS2Vuc2hpbiohAAAAAABo/PxDCE1yIFdvb2R5AAAAAAA0FRpECUdfQV9NX0UAAAAAADSdLEQKTDBSRAAAAAAAXVUYRQtsdWtlIGN1bmlhbAAAAAAApc+2RQxbc3BsXSBidWJiYSBTSU4AAAAAACKWiEQNZ3J1bnQAAAAAAP7EV0EOQ29sLk5hdGhhblJKZXNzdXAAAgAAAEgG/0MPTmV3YgAAAAAANNx1RBBIdWdlIG1vZm8gUm9hY2gAAQAAACDo1kIRU3lkAAAAAACg0INCEk5pZ2h0WmVyb1gAAAAAAEgG/0MTdWllb3cAAAAAACJgjUQURFRBIFtdIExlQmFOb04AAQAAAF/WEEUVTlNQbGF5ZXIAAAAAADRTekQWWy1pb1AtXSBZYUt1ekEAAAAAAB1RCkUXbXVyb34AAAAAAKo61kQYLS9BVVMvLSBHcmV0Y2hlbgAAAAAAImaIRA==');
$packets['HalfLife']['infostring']  = base64_decode('/////2luZm9zdHJpbmdyZXNwb25zZQBccHJvdG9jb2xcNDdcYWRkcmVzc1wyMDIuMTIuMTQ3LjExMToyNzA0NVxwbGF5ZXJzXDExXHByb3h5dGFyZ2V0XDBcbGFuXDBcbWF4XDI0XGJvdHNcMFxnYW1lZGlyXG5zXGRlc2NyaXB0aW9uXE5TIHYzLjAgYmV0YSA1XGhvc3RuYW1lXEdhbWVBcmVuYSAtIE5hdFNlbCAjNCAtIENsYXNzaWMgT25seVxtYXBcbnNfdGFuaXRoXHR5cGVcZFxwYXNzd29yZFwwXG9zXHdcc2VjdXJlXDBcbW9kXDFcbW9kdmVyc2lvblwwXHN2b25seVwwXGNsZGxsXDAA');

// GameSpy04
$packets['GameSpy04']['serverinfo'] = base64_decode('AE5HU1Fob3N0bmFtZQAzRkwgVklDIC0gQkYgVmlldG5hbSAxLjIxAGhvc3Rwb3J0ADE1NTY3AG1hcG5hbWUAT1BFUkFUSU9OIEZMQU1JTkcgREFSVABnYW1ldHlwZQBjb25xdWVzdABudW1wbGF5ZXJzADAAbWF4cGxheWVycwAzMgBnYW1lbW9kZQBvcGVucGxheWluZwBwYXNzd29yZAAwAGdhbWV2ZXIAdjEuMjEAZGVkaWNhdGVkADIAc3RhdHVzADMAZ2FtZV9pZABiZnZpZXRuYW0AbWFwX2lkAEJGVmlldG5hbQBzdl9wdW5rYnVzdGVyADEAdGltZWxpbWl0ADMwAG51bWJlcl9vZl9yb3VuZHMAMgBzcGF3bl93YXZlX3RpbWUAMTVzAHNwYXduX2RlbGF5ADVzAHNvbGRpZXJfZnJpZW5kbHlfZmlyZQAxMDAlAHZlaGljbGVfZnJpZW5kbHlfZmlyZQAxMDAlAGdhbWVfc3RhcnRfZGVsYXkAMjBzAHRpY2tldF9yYXRpbwAxMDAlAGFsbG93X25vc2VfY2FtAHllcwBleHRlcm5hbF92aWV3AG9uAHVzX3RlYW1fcmF0aW8AMQBudmFfdGVhbV9yYXRpbwAxAGJhbmR3aWR0aF9jaG9rZV9saW1pdAAwAGZyZWVfY2FtZXJhAG9mZgBhdXRvX2JhbGFuY2VfdGVhbXMAb2ZmAG5hbWVfdGFnX2Rpc3RhbmNlADEwMABuYW1lX3RhZ19kaXN0YW5jZV9zY29wZQAzMDAAa2lja2JhY2sAMCUAa2lja2JhY2tfb25fc3BsYXNoADAlAHNvbGRpZXJfZnJpZW5kbHlfZmlyZV9vbl9zcGxhc2gAMTAwJQB2ZWhpY2xlX2ZyaWVuZGx5X2ZpcmVfb25fc3BsYXNoADEwMCUAY3B1ADIzOTUAYm90X3NraWxsAAByZXNlcnZlZHNsb3RzADAAc3BlY3RhdG9yc2FsbG93ZWQAMQBzcGVjdGF0b3J2b3RpbmcAMABzcGVjdGF0b3Jzd2l0Y2h0aW1lADEyMHMAYWN0aXZlX21vZHMALABhbGxfYWN0aXZlX21vZHMAYmZ2X3d3Mm1vZCwgYmZ2aWV0bmFtAGdhbWVfaWRfbmFtZQBCRlZpZXRuYW0AAA==');
$packets['GameSpy04']['playerinfo'] = base64_decode('AE5HU1EAKnBsYXllcl8Ac2NvcmVfAGRlYXRoc18AcGluZ18AdGVhbV8Aa2lsbHNfAABCb29uY2hvbwA2ADUAMjUAMgA2AC09QU1JPS0gKDEzKQAxNQAxNQA0OQAxADE1AFdFRU1BTgAxNAAxMwAzNQAxADE0AFByZXRlbmQgS2lsbGVyADgAMTMANDYAMQA4AFdvbWJhdAA5ADEyADkAMgA5AFNweWRhSAAwADAAMzcAMQAwAHdheGxpbmcAMTMAMTAANjYAMgAxMABbQm5HXSBDYXJuADgANAAxNgAxADgAUGlja2xldHJvbjIwMDIANAAxNgA1MwAyADQAW1RFQV0gVG9zc2VkIFNhbGFkADE3ADE0ADM3ADEAMTcAUmFpbiBPZiBUZXJyYQAxMgA3ADcxADEAMTIAQnVsbGV0UmlkZGRlbgAxNAA1ADU2ADEAMTAAW0U9TUOyXSAtIGthdGFuYQAyADE3ADUyADEANABbRT1NQ7JdIC0gYm9uZWh1bnRlcgAxMQAxMgA2NQAxADgAc2lja3JlADcAMTYANDQAMQA3AFNoaWZ0eVJ1c3NpYW5NZWRSZXBhaXIAMwAxMQAzMAAxADMAW0lCXUhhd2tzdGEANAAyADU2ADIANgBkciBjaGVla3MAMAAwADY2ADIAMAC7qVarICBUrklHR+IAMTUAMTIAMTUAMgAxNQBbVEVBXXNoYWRvd3J1bm5lcgAyNgAxMgAzOQAyADI2ALupVqsgVGVaWmEANwAxNAA1MQAxADcAKltTaGFEb3ddADE4ADUAMzQAMgAxOAA9W1ZpT2xhdG9yXT0AMTQAMTEAMjcAMQAxNABWaW0gRnVlZ28ANgAxMQAxMTcAMgA2AFtFPU1Dsl0gLSBQYW50aGVyADkAMTMAODgAMQA5AFtJQl0udGlwcGVSADEzADEyADQ2ADIAMTMARmlzdHkAMjcANwAxMQAyADI0AEhvbWljaWRhbCBIZXJvADQAMTAAMjQAMQA0ADstKQAxNgA3ADU1ADIAMTAAbW9ua2V5X21hZ2ljADExADEwADM2ADIAMTEATWV0YWxfSmFja2V0ADEyADUANzUAMQAxMgBhemEAMTMAMTMAMzUAMgAxMQBNanIgR3JhdmUgRGlnZ2VyADYAOQAzMAAxADYAQmVzdHJhZmUgTWljaAA2ADkANDQAMQA2AChPVFRQKVRhc3R5TmFwYWxtADUANgAyMwAyADUAVEhFUExBWUVSADEwADEwADQxADEAMTAARGplbngAOAA2ADMyADIAOABGcmVuY2ggV2FycmlvcgA0ADUANDg1ADIANAAoT1RUUClSMUJhbmRpdAAxADUANzQAMgAxAEppbSBSYW5kb2xwaAAwADIAMTY2ADEAMABUaG9ydXMAMAAwADM3MgAyADAAcGxheWVyADAAMQAyNzIAMgAwAAACdGVhbV90AHRpY2tldHNfdAAAcmVkADg4AGJsdWUAOTQA');

// HalfLife2
$packets['HalfLife2']['players']    = base64_decode('/////0QQAGltIDUgd290cyB5b3VyIGV4Y3VzZQAFAAAAUQGZRQFaZXVzX2tpbmcgb2YgYWxsIGdvZHMACQAAALkhSUQCU2FyY2FzdGljX0Jhc3RhcmQABQAAAHWdZUQDb25lY2VsbAAKAAAANsMCRQRLZW5zaGluR0IAAgAAAOPVMEQFZmx5ZmlubgAFAAAA6PFgRAZBbnR6UGFudHogfCBPMTEgfAAAAAAAH8y7QwdbUkxDXSA5RG9yZgABAAAAA+O1QwhnZWZvcmNlMmd0czMybWIABAAAAACyykUJRWFaYUwAAAAAADhcsEMKWypBckEqXVJlZG1hbnxURkh8AAsAAAA0u+xFC1tDbGFubGVzc11RdWlja3NpbHZlcgAAAAAARNUbRQxtYWRlIGluIGlyYXEAAAAAAIep/EUNdm1hY2MADwAAANkPEUUOSm9oYVppABoAAACy5qtED0FwaWxhawACAAAAAaQmRA==');
$packets['HalfLife2']['status']     = base64_decode('/////0kHSW50ZXJub2RlIENTOlMgIzAxICgtRkYpAGNzX2hhdmFuYQBjc3RyaWtlAENvdW50ZXItU3RyaWtlOiBTb3VyY2UAAAAAEABkbAAAMS4wLjAuMTMA');
$packets['HalfLife2']['rules']      = base64_decode('/////0UtAG1wX3RlYW1wbGF5ADAAbXBfZnJhZ2xpbWl0ADAAbXBfZmFsbGRhbWFnZQAwAG1wX3dlYXBvbnN0YXkAMABtcF9mb3JjZXJlc3Bhd24AMQBtcF9mb290c3RlcHMAMQBtcF9mbGFzaGxpZ2h0ADEAbXBfYXV0b2Nyb3NzaGFpcgAxAGRlY2FsZnJlcXVlbmN5ADEwAG1wX3RlYW1saXN0AGhncnVudDtzY2llbnRpc3QAbXBfYWxsb3dOUENzADEAc3ZfYWxsdGFsawAwAG1wX3RpbWVsaW1pdAAzMABzdl9ncmF2aXR5ADgwMABzdl9zdG9wc3BlZWQANzUAc3Zfbm9jbGlwYWNjZWxlcmF0ZQA1AHN2X25vY2xpcHNwZWVkADUAc3Zfc3BlY2FjY2VsZXJhdGUANQBzdl9zcGVjc3BlZWQAMwBzdl9zcGVjbm9jbGlwADEAc3ZfbWF4c3BlZWQAMzIwAHN2X2FjY2VsZXJhdGUANQBzdl9haXJhY2NlbGVyYXRlADEwAHN2X3dhdGVyYWNjZWxlcmF0ZQAxMABzdl93YXRlcmZyaWN0aW9uADEAc3ZfZm9vdHN0ZXBzADEAc3Zfcm9sbHNwZWVkADIwMABzdl9yb2xsYW5nbGUAMABzdl9mcmljdGlvbgA0AHN2X2JvdW5jZQAwAHN2X3N0ZXBzaXplADE4AHJfVmVoaWNsZVZpZXdEYW1wZW4AMQByX0plZXBWaWV3RGFtcGVuRnJlcQA3LjAAcl9KZWVwVmlld0RhbXBlbkRhbXAAMS4wAHJfSmVlcFZpZXdaSGVpZ2h0ADEwLjAAcl9BaXJib2F0Vmlld0RhbXBlbkZyZXEANy4wAHJfQWlyYm9hdFZpZXdEYW1wZW5EYW1wADEuMAByX0FpcmJvYXRWaWV3WkhlaWdodAAwLjAAbXBfZnJpZW5kbHlmaXJlADAAY29vcAAwAGRlYXRobWF0Y2gAMQBzdl92b2ljZWVuYWJsZQAxAHN2X3Bhc3N3b3JkADAAc3ZfcGF1c2FibGUAMABzdl9jaGVhdHMAMAA=');

// Quake3
$packets['Quake3']['info']          = base64_decode('/////2luZm9SZXNwb25zZQpccHJvdG9jb2xcNlxob3N0bmFtZVxeMU9wdHVzTmV0IENPRCBIZWFkUXVhcnRlcnMgIzFcbWFwbmFtZVxtcF9kYXdudmlsbGVcY2xpZW50c1w2XHN2X21heGNsaWVudHNcMThcZ2FtZXR5cGVcaHFccHVyZVwxXG1heFBpbmdcNTAwXGtjXDFcaHdcMVxwYlwxXG1vZFwx');
$packets['Quake3']['status']        = base64_decode('/////3N0YXR1c1Jlc3BvbnNlClx2ZXJzaW9uXFEzIDEuMzJiIGxpbnV4LWkzODYgTm92IDE0IDIwMDJcZG1mbGFnc1wwXGZyYWdsaW1pdFwwXHRpbWVsaW1pdFw2MFxnX2dhbWV0eXBlXDhccHJvdG9jb2xcNjhcbWFwbmFtZVxyYTNtYXAxMVxzdl9wcml2YXRlQ2xpZW50c1wwXHN2X2hvc3RuYW1lXE9wdHVzTmV0IFJBMyAjMlxzdl9tYXhjbGllbnRzXDIwXHN2X3B1bmtidXN0ZXJcMFxzdl9tYXhSYXRlXDEyMDAwXHN2X21pblBpbmdcMFxzdl9tYXhQaW5nXDBcc3ZfZmxvb2RQcm90ZWN0XDBcc3ZfYWxsb3dEb3dubG9hZFwwXGxvY2F0aW9uXDBcZ2FtZW5hbWVcYXJlbmFcZ19tYXhHYW1lQ2xpZW50c1wwXGNhcHR1cmVsaW1pdFw4XGdfbmVlZHBhc3NcMFxnX3ZlcnNpb25cUkEzIDEuNzYgQXByIDIzIDIwMDQgMTA6MTc6MTdcZ190aW1lTGVmdFw1NQo1IDMxICJpbGxpZGFuIgo2IDM5ICJeMHgyXjR8XjdMb3JlbWFuIgowIDc3ICJkdW1iZ2VlayIKMiAyOSAiXjRWdWxeN25lcmFibGUgQ2hveSIKOCAzMSAiQW5hbCBXYXJyaW9yIgowIDMwICJeMi09XjFSYW5kXjI9LSIKMSAzMSAiTXJCb2JIYXJyaXMiCg==');

?>