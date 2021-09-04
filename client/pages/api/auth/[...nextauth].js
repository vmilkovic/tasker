import NextAuth from "next-auth";
import Providers from "next-auth/providers";

const options = {
  providers: [
    Providers.Credentials({
      name: "Credentials",
      credentials: {
        username: { label: "Username", type: "text", placeholder: "jsmith" },
        password: { label: "Password", type: "password" },
      },
      authorize(credentials, req) {
        const user = { name: "Vedran Milković", email: "vedran@milkovic.dev" };
        if (user) {
          return user;
        }
        return null;
      },
    }),
    Providers.Google({
      clientId: process.env.GOOGLE_CLIENT_ID,
      clientSecret: process.env.GOOGLE_CLIENT_SECRET,
    }),
  ],
  callbacks: {
    async signIn(user, account, profile) {
      return true;
    },
    async redirect(url, baseUrl) {
      return baseUrl;
    },
    async session(session, user) {
      return session;
    },
    async jwt(token, user, account, profile, isNewUser) {
      console.log(isNewUser);
      //TODO create or validate user and create database credentials token (uuid, name, email)
      return token;
    },
  },
  pages: {
    signIn: "/auth/signin",
  },
  session: {
    jwt: true,
  },
  debug: false,
};

export default (req, res) => NextAuth(req, res, options);
