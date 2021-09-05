import React from "react";
import {
  getProviders,
  signIn,
  getSession,
  getCsrfToken,
} from "next-auth/client";

export default function SignIn({ providers, csrfToken }) {
  return (
    <div>
      <h1>Welcome to Auth page</h1>
      <div>
        <div>
          <form method="post" action="/api/auth/callback/credentials/">
            <input name="csrfToken" type="hidden" defaultValue={csrfToken} />
            <label>
              Username
              <input name="username" type="text" />
            </label>
            <label>
              Password
              <input name="password" type="password" />
            </label>
            <button type="submit">Sign in</button>
          </form>
        </div>
        <div>
          {Object.values(providers).map((provider) => {
            if (provider.id === "credentials") {
              return;
            }

            return (
              <div key={provider.name}>
                <button onClick={() => signIn(provider.id)}>
                  Sign with {provider.name}
                </button>
              </div>
            );
          })}
        </div>
      </div>
    </div>
  );
}

export async function getServerSideProps(context) {
  const { req, res } = context;
  const session = await getSession({ req });

  return {
    props: {
      csrfToken: await getCsrfToken(context),
      providers: await getProviders(),
    },
  };
}
