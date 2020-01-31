#!/usr/bin/env python

from Crypto.Cipher import AES # Library: PyCryptodome

encryption_key = bytes.fromhex('5AD84121D9D20B364B7A11F3C1B5827F') # gpk60
authentication_key = bytes.fromhex('AFB3F93E3E7204EDB3C27F96DBD51AE0') # gpk61
header = '7E A1 E9 41 03 13 C6 37 E6 E7 00' # bruges ikke ifm. dekryptering


# +--------+---------------+---------+-----------------+-------------+----------+
# | 1byte  | len+8bytes    | xbytes  | 1or5bytes       | ybytes      | 12bytes  |
# +--------+---------------+---------+-----------------+-------------+----------+
# | Tag    | System title  | Length  | Security header | Cipher text | Auth tag |
# +--------+---------------+---------+-----------------+-------------+----------+
#  Invokation
# TlSTLS  Counter Data(Cipher text) sidste 12 bytes er Auth tag


cipher_text = 'DB 08 4B 41 4D 45 01 A4 DC 52 82 01 D0 30 00 07 88 E1 A0 39 B2 D1 4C 71 2D D4 D8 C8 44 0D 53 68 E4 33 BD 70 B7 36 81 E9 A9 EF FE 38 F1 75 A3 7D E9 CD E6 4E 8F 78 0D 8F 18 B4 3F C0 59 D8 79 02 F3 D7 47 B8 14 BC D0 6A 47 00 68 78 01 BD 5D 06 61 20 54 50 7D 44 E7 66 98 CC 3E 35 CC 9D E6 2C 28 4C 0D EE A6 35 B9 BF C5 6C E1 FE 5A 3A 1E 5E 27 0B 0C 18 1A CF 02 15 1F CC 59 21 34 D0 4F 02 92 B5 A3 53 38 D7 B7 81 BB 1F 2A 7E 40 71 81 5C EE D5 D4 BA C6 EF AA AF F9 79 EF 96 9D 0D B4 6F 51 E5 E0 FC 00 F5 AD 10 5F BE F9 5F C5 F8 85 46 0B 56 32 55 4A C0 5D 9E B4 F6 5F F2 23 97 2A 47 CF D4 34 B5 F5 E2 D8 53 EA 4C 14 72 75 86 F0 E2 1C 6E E1 25 26 8C B4 DC 7E C5 B1 0F 84 83 C0 10 C8 E6 88 DF 86 58 4D 7C 29 D8 17 31 A4 E0 96 91 41 B6 AD D9 42 E4 0A 96 E3 E2 DC 2F 90 20 BF 9D 58 02 A1 8D C9 85 BB 54 22 F4 70 C0 62 9D 22 DB 6F 16 B6 64 7D B3 C9 F7 27 C1 70 C2 DB 4C 9A 23 0F 82 83 46 E8 6F 56 D3 47 B6 2B FE 28 A5 07 AE 2A 21 9A CC 63 AD 5E E0 6E CB 94 9D C4 FD C2 D2 F8 08 02 7A 4A 4C 67 7C 93 C4 C8 90 9D 73 36 D8 B8 B3 79 18 36 C5 55 5E 74 E2 54 82 A4 4A 5F 6D 35 8D CE 84 50 47 8F 8B 2C 5D 56 66 C2 CF 8B D1 90 D4 87 4E 70 7F 5D 4B CD E9 4E 91 35 5A 81 39 C3 E4 1C D5 F0 88 99 B6 5E E9 B5 9F C6 03 72 E4 F9 2B C9 98 26 B8 C1 47 F2 09 5F B3 8A 89 14 09 AA 81 E2 27 07 6C 21 CB 7C AF 73 B3 E8 A4 E2 56 C7 0D 95 47 A9 14 F3 9C 16 93 76 BF 92 2B 08 06 4E C4 FB 31 D4 4E CD 72 1D 1A 15 1E 4E 68 4F 0B 26 85 C4 B6 9D 96 F5 FB 52 D0 B8 12 79'

system_title = cipher_text[2:2+8]
initialization_vector = system_title + cipher_text[14:14+4]
additional_authenticated_data = cipher_text[13:13+1] + authentication_key
authentication_tag = cipher_text[len(cipher_text)-12:len(cipher_text)]

cipher = AES.new(encryption_key, AES.MODE_GCM, nonce=initialization_vector, mac_len=len(authentication_tag)) #len(authentication_tag) 4..14
cipher.update(additional_authenticated_data)
plaintext = cipher.decrypt_and_verify(cipher_text[18:len(cipher_text)-12],authentication_tag)
print(plaintext.hex())

